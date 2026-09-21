<?php

namespace App\Http\Controllers;

use App\Enums\PointChargeStatus;
use App\Enums\PointHistoryType;
use App\Http\Requests\Point\IntentRequest;
use App\Http\Requests\Point\StoreRequest;
use App\Models\PointCharge;
use App\Models\PointHistory;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Stripe\Exception\ApiErrorException;

class PointController extends Controller
{
    public function __construct(private readonly StripeService $stripeService) {}

    /**
     * ポイントチャージフォーム
     *
     * @return View
     */
    public function index(): View
    {
        $user = auth()->user();
        $pointPlans = collect(config('points.plans'));
        // 登録済みのクレジットカード取得
        $savedCards = $this->stripeService->getPaymentMethods($user);

        return view('point.index', compact('pointPlans', 'savedCards'));
    }

    /**
     * PaymentIntent作成 (Ajaxで使用)
     */
    public function createIntent(IntentRequest $request): JsonResponse
    {
        $user = auth()->user();
        $plan = collect(config('points.plans'))
            ->firstWhere('id', $request->integer('plan_id'));

        try {
            $paymentIntent = $this->stripeService->createPaymentIntent($plan['amount'], $user);

            return response()->json(['client_secret' => $paymentIntent->client_secret]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe: PaymentIntent作成失敗', [
                'user_id' => $user->id,
                'plan_id' => $plan['id'],
                'message' => $e->getMessage(),
            ]);

            return response()->json(['error' => '決済処理の開始に失敗しました。'], 500);
        }
    }

    /**
     * 保存済みカード削除
     *
     * @param string $paymentMethodId
     * @return RedirectResponse
     */
    public function destroyPaymentMethod(string $paymentMethodId): RedirectResponse
    {
        $user = auth()->user();

        try {
            $this->stripeService->detachPaymentMethod($paymentMethodId, $user);
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('point.index')->with('error', $e->getMessage());
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe: カードの削除に失敗しました', [
                'user_id'           => $user->id,
                'payment_method_id' => $paymentMethodId,
                'message'           => $e->getMessage(),
            ]);

            return redirect()->route('point.index')->with('error', 'カードの削除に失敗しました。');
        }

        return redirect()->route('point.index')->with('success', 'カードを削除しました。');
    }

    /**
     * ポイント購入処理
     *
     * @throws \Throwable
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $plan = collect(config('points.plans'))
            ->firstWhere('id', $request->integer('plan_id'));
        $paymentIntentId = $request->input('payment_intent_id');

        // 重複チャージ防止チェック
        if (PointCharge::where('transaction_id', $paymentIntentId)->exists()) {
            return redirect()->route('point.index')->with('error', '同じ取引が既に処理されています。');
        }

        // 決済情報確認
        try {
            $paymentIntent = $this->stripeService->retrievePaymentIntent($paymentIntentId);
        } catch (ApiErrorException $e) {
            Log::error('Stripe: 決済情報の確認に失敗しました', [
                'user_id' => $user->id,
                'payment_intent_id' => $paymentIntentId,
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('point.index')->with('error', '決済情報の確認に失敗しました。');
        }

        if ($paymentIntent->status !== 'succeeded') {
            Log::warning('Stripe: 決済が完了していません', [
                'user_id' => $user->id,
                'payment_intent_id' => $paymentIntentId,
                'status' => $paymentIntent->status,
            ]);

            return redirect()->route('point.index')->with('error', '決済が完了していません。');
        }

        // 金額チェック
        if ($paymentIntent->amount !== $plan['amount']) {
            Log::error('Stripe: 決済金額が一致しません', [
                'user_id' => $user->id,
                'payment_intent_id' => $paymentIntentId,
                'expected' => $plan['amount'],
                'actual' => $paymentIntent->amount,
            ]);

            return redirect()->route('point.index')->with('error', '決済金額が一致しません。');
        }

        // DB保存
        try {
            DB::transaction(function () use ($user, $plan, $paymentIntentId) {
                // ポイントチャージ履歴登録
                $pointCharge = PointCharge::create([
                    'user_id' => $user->id,
                    'point' => $plan['point'],
                    'amount' => $plan['amount'],
                    'payment_method' => 'credit_card',
                    'status' => PointChargeStatus::Completed->value,
                    'transaction_id' => $paymentIntentId,
                    'charged_at' => now(),
                ]);

                $balanceAfter = $user->point + $plan['point'];
                // ユーザーポイント加算
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
        } catch (\Throwable $e) {
            Log::error('Stripe: DB保存失敗', [
                'user_id' => $user->id,
                'payment_intent_id' => $paymentIntentId,
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('point.index')->with('error', 'ポイントの付与に失敗しました。サポートにお問い合わせください。');
        }

        return redirect()->route('mypage')->with('success', number_format($plan['point']) . 'ptをチャージしました。');
    }
}
