<?php

namespace App\Http\Controllers;

use App\Enums\PointChargeStatus;
use App\Enums\PointHistoryType;
use App\Http\Requests\Point\StoreRequest;
use App\Models\PointCharge;
use App\Models\PointHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PointController extends Controller
{
    /**
     * ポイントチャージ
     *
     * @return View
     */
    public function index(): View
    {
        $pointPlans = collect(config('points.plans'));

        return view('point.index', compact('pointPlans'));
    }

    /**
     * ポイント購入処理
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $plan = collect(config('points.plans'))
            ->firstWhere('id', $request->integer('plan_id'));

        // TODO: クレジット決済処理

        DB::transaction(function () use ($user, $plan) {
            // ポイントチャージ履歴登録
            $pointCharge = PointCharge::create([
                'user_id' => $user->id,
                'point' => $plan['point'],
                'amount' => $plan['amount'],
                'payment_method' => 'credit_card',
                'status' => PointChargeStatus::Completed->value,
                'charged_at' => now(),
            ]);

            $balanceAfter = $user->point + $plan['point'];

            // ユーザーポイント追加
            $user->increment('point', $plan['point']);

            // ポイント履歴登録
            PointHistory::create([
                'user_id' => $user->id,
                'type' => PointHistoryType::Charge->value,
                'point' => $plan['point'],
                'related_type' => PointCharge::class,
                'related_id' => $pointCharge->id,
                'balance_after' => $balanceAfter,
            ]);
        });

        return redirect()->route('mypage')->with('success', number_format($plan['point']) . 'ptをチャージしました。');
    }
}
