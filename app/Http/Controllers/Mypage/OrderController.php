<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * 購入履歴
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = auth()->user()
            ->orders()
            ->with('orderDetails.product.category')
            ->latest();

        if ($request->filled('category_id')) {
            $query->whereHas('orderDetails.product', function ($subQuery) use ($request) {
                $subQuery->where('category_id', $request->integer('category_id'));
            });
        }

        $orders = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();

        return view('mypage.orders.index', compact('orders', 'categories'));
    }

    /**
     * 購入履歴詳細
     *
     * @param Order $order
     * @return View
     */
    public function show(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('orderDetails.product.category', 'orderDetails.productAccounts');

        return view('mypage.orders.show', compact('order'));
    }
}
