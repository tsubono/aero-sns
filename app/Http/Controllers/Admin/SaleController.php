<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SaleController extends Controller
{
    /**
     * 売上管理
     *
     * @return View
     */
    public function index(): View
    {
        $totalOrders = Order::count();
        $totalPoints = Order::sum('total_point');

        // 月別売上
        $monthlySales = Order::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as order_count, SUM(total_point) as monthly_point")
            ->groupBy('month')
            ->orderByDesc('month')
            ->limit(12)
            ->get();

        // カテゴリ別売上
        $categoryRanking = OrderDetail::join('products', 'order_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(order_details.point * order_details.quantity) as total_point'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_point')
            ->get();

        // 最近の注文
        $recentOrders = Order::with('user', 'orderDetails.product')
            ->latest()
            ->paginate(20);

        return view('admin.sales.index', compact('totalOrders', 'totalPoints', 'monthlySales', 'categoryRanking', 'recentOrders'));
    }
}
