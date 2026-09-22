<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PointHistoryType;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PointHistory;
use App\Models\Product;
use App\Models\ProductAccount;
use App\Http\Requests\Order\BuyNowRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * 注文確認
     *
     * @return View|RedirectResponse
     */
    public function index(): View|RedirectResponse
    {
        $user = auth()->user();
        $cartItems = $user->carts()->with('product.category')->get();

        $totalPoint = $cartItems->sum(fn($cartItem) => $cartItem->product->point * $cartItem->quantity);
        $userPoint = $user->point;

        if ($userPoint < $totalPoint) {
            return redirect()->route('point.index')->with('error', 'ポイントが不足しています。ポイントをチャージしてから再度お試しください。');
        }

        $totalQty = $cartItems->sum('quantity');

        return view('order.index', compact('cartItems', 'totalPoint', 'totalQty', 'userPoint'));
    }

    /**
     * 注文処理
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $cartItems = $user->carts()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'カートに商品がありません。');
        }

        $totalPoint = $cartItems->sum(fn($cartItem) => $cartItem->product->point * $cartItem->quantity);

        // ポイントチェック
        if ($user->point < $totalPoint) {
            return redirect()->route('point.index')->with('error', 'ポイントが不足しています。ポイントをチャージしてから再度お試しください。');
        }

        // 在庫チェック
        foreach ($cartItems as $cartItem) {
            $available = $cartItem->product->productAccounts()->where('is_used', false)->count();
            if ($available < $cartItem->quantity) {
                return redirect()->route('order.index')->with('error', "「{$cartItem->product->name}」の在庫が不足しています。");
            }
        }

        $order = DB::transaction(function () use ($user, $cartItems, $totalPoint) {
            // 注文登録
            $order = $this->createOrder($user, $totalPoint);
            // 注文詳細登録
            foreach ($cartItems as $cartItem) {
                $this->createOrderDetail($order, $cartItem->product, $cartItem->quantity);
            }
            // 支払い処理
            $this->processPayment($user, $order, $totalPoint);
            // カート削除処理
            $user->carts()->delete();

            return $order;
        });

        return redirect()->route('order.complete')->with('order_id', $order->id);
    }

    /**
     * 今すぐ購入処理
     *
     * @param BuyNowRequest $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function buyNow(BuyNowRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $product = Product::findOrFail($request->integer('product_id'));
        $quantity = $request->integer('quantity');

        $totalPoint = $product->point * $quantity;

        // ポイントチェック
        if ($user->point < $totalPoint) {
            return redirect()->route('point.index')->with('error', 'ポイントが不足しています。ポイントをチャージしてから再度お試しください。');
        }

        // 在庫チェック
        $available = $product->productAccounts()->where('is_used', false)->count();
        if ($available < $quantity) {
            return redirect()->back()->with('error', '在庫が不足しています。');
        }

        $order = DB::transaction(function () use ($user, $product, $quantity, $totalPoint) {
            // 注文登録
            $order = $this->createOrder($user, $totalPoint);
            // 注文詳細登録
            $this->createOrderDetail($order, $product, $quantity);
            // 支払い処理
            $this->processPayment($user, $order, $totalPoint);

            return $order;
        });

        return redirect()->route('order.complete')->with('order_id', $order->id);
    }

    /**
     * 注文完了
     *
     * @param Request $request
     * @return View
     */
    public function complete(Request $request): View
    {
        $orderId = $request->session()->get('order_id');
        $order = $orderId ? Order::find($orderId) : null;

        return view('order.complete', compact('order'));
    }

    /**
     * 注文登録処理
     *
     * @param User $user
     * @param int $totalPoint
     * @return Order
     * @throws \Random\RandomException
     */
    private function createOrder(User $user, int $totalPoint): Order
    {
        return Order::create([
            'order_no' => 'AS-' . now()->format('Ymd') . '-' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT),
            'user_id' => $user->id,
            'total_point' => $totalPoint,
            'status' => OrderStatus::Completed->value,
        ]);
    }

    /**
     * 注文詳細登録処理
     *
     * @param Order $order
     * @param Product $product
     * @param int $quantity
     * @return void
     */
    private function createOrderDetail(Order $order, Product $product, int $quantity): void
    {
        // 注文詳細登録
        $detail = OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'point' => $product->point,
            'quantity' => $quantity,
        ]);

        // アカウント情報をランダムに取得して登録
        $accounts = $product->productAccounts()
            ->where('is_used', false)
            ->inRandomOrder()
            ->limit($quantity)
            ->get();

        $detail->productAccounts()->attach($accounts->pluck('id'));

        // アカウント情報を使用済みに更新
        ProductAccount::whereIn('id', $accounts->pluck('id'))->update(['is_used' => true]);
    }

    /**
     * 支払い処理
     *
     * @param User $user
     * @param Order $order
     * @param int $totalPoint
     * @return void
     */
    private function processPayment(User $user, Order $order, int $totalPoint): void
    {
        $balanceAfter = $user->point - $totalPoint;

        // ユーザーポイント消費
        $user->decrement('point', $totalPoint);

        // ポイント履歴登録
        PointHistory::create([
            'user_id' => $user->id,
            'type' => PointHistoryType::Use->value,
            'point' => -$totalPoint,
            'related_type' => Order::class,
            'related_id' => $order->id,
            'balance_after' => $balanceAfter,
        ]);
    }
}
