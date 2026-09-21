<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointCharge;
use Illuminate\View\View;

class PointChargeController extends Controller
{
    /**
     * ポイントチャージ履歴一覧
     *
     * @return View
     */
    public function index(): View
    {
        $pointCharges = PointCharge::with('user')->latest()->paginate(30);
        $totalAmount = PointCharge::where('status', \App\Enums\PointChargeStatus::Completed)->sum('amount');
        $totalPoint = PointCharge::where('status', \App\Enums\PointChargeStatus::Completed)->sum('point');

        return view('admin.point-charges.index', compact('pointCharges', 'totalAmount', 'totalPoint'));
    }
}
