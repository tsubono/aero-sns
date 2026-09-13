<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MypageController extends Controller
{
    /**
     * マイページ
     *
     * @return View
     */
    public function index(): View
    {
        // 最近の注文
        $recentOrders = auth()->user()
            ->orders()
            ->with('orderDetails.product.category')
            ->latest()
            ->limit(3)
            ->get();

        return view('mypage.index', compact('recentOrders'));
    }
}
