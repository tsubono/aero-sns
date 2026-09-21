<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\StripeClient;

class StripeService
{
    private StripeClient $client;

    public function __construct()
    {
        $this->client = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * StripeCustomerを取得または新規作成 & stripe_customer_idをDBに保存
     *
     * @throws ApiErrorException
     */
    public function getOrCreateCustomer(User $user): Customer
    {
        if ($user->stripe_customer_id) {
            return $this->client->customers->retrieve($user->stripe_customer_id);
        }

        $customer = $this->client->customers->create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => ['user_id' => $user->id],
        ]);

        $user->update(['stripe_customer_id' => $customer->id]);

        return $customer;
    }

    /**
     * 顧客に紐づくクレジットカード一覧を取得
     *
     * @return PaymentMethod[]
     */
    public function getPaymentMethods(User $user, $type = 'card'): array
    {
        if (!$user->stripe_customer_id) {
            return [];
        }

        try {
            $result = $this->client->customers->allPaymentMethods(
                $user->stripe_customer_id,
                ['type' => $type]
            );

            return $result->data;
        } catch (ApiErrorException $e) {
            Log::error('Stripe: 保存済みカード取得失敗', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * PaymentIntentを作成
     *
     * @throws ApiErrorException
     */
    public function createPaymentIntent(int $amountYen, User $user): PaymentIntent
    {
        $customer = $this->getOrCreateCustomer($user);

        return $this->client->paymentIntents->create([
            'amount' => $amountYen,
            'currency' => 'jpy',
            'customer' => $customer->id,
            'setup_future_usage' => 'off_session',
            'payment_method_types' => ['card'],
        ]);
    }

    /**
     * PaymentIntentを取得
     *
     * @throws ApiErrorException
     */
    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent
    {
        return $this->client->paymentIntents->retrieve($paymentIntentId);
    }

    /**
     * 保存済みカードを削除
     *
     * @throws ApiErrorException|\InvalidArgumentException
     */
    public function detachPaymentMethod(string $paymentMethodId, User $user): void
    {
        $paymentMethod = $this->client->paymentMethods->retrieve($paymentMethodId);

        if ($paymentMethod->customer !== $user->stripe_customer_id) {
            throw new \InvalidArgumentException('指定されたカードはこのユーザーに紐付いていません。');
        }

        $this->client->paymentMethods->detach($paymentMethodId);
    }
}
