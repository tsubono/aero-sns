<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\StoreRequest;
use App\Http\Requests\Cart\UpdateRequest;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * カート
     *
     * @return View
     */
    public function index(): View
    {
        $cartItems = auth()->user()
            ->carts()
            ->with('product.category')
            ->get();

        $totalPoint = $cartItems->sum(fn($cartItem) => $cartItem->product->point * $cartItem->quantity);
        $totalQty = $cartItems->sum('quantity');

        return view('cart.index', compact('cartItems', 'totalPoint', 'totalQty'));
    }

    /**
     * カートに追加処理
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $product = Product::findOrFail($request->integer('product_id'));

        $cartItem = auth()->user()
            ->carts()
            ->where('product_id', $product->id)
            ->first();

        // カートに既に商品が存在する場合
        if ($cartItem) {
            // 数量を更新
            $newQuantity = min($product->stock, $cartItem->quantity + $request->integer('quantity'));
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // カートに追加
            auth()->user()->carts()->create([
                'product_id' => $product->id,
                'quantity' => min($product->stock, $request->integer('quantity')),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'カートに追加しました。');
    }

    /**
     * カート更新処理
     *
     * @param UpdateRequest $request
     * @param Cart $cart
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, Cart $cart): RedirectResponse
    {
        $delta = $request->integer('delta');
        $newQuantity = $cart->quantity + $delta;

        // 数量が0以下の場合
        if ($newQuantity <= 0) {
            // カートから削除
            $cart->delete();
        } else {
            // 数量更新
            $maxQuantity = $cart->product->stock;
            $cart->update(['quantity' => min($maxQuantity, $newQuantity)]);
        }

        return redirect()->route('cart.index');
    }

    /**
     * カートから削除処理
     *
     * @param Cart $cart
     * @return RedirectResponse
     */
    public function destroy(Cart $cart): RedirectResponse
    {
        $cart->delete();

        return redirect()->route('cart.index');
    }
}
