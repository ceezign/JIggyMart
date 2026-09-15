<?php

namespace App\Services;

use App\Exceptions\PaymentFailedException;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Payment processing is kept deliberately separate from order/inventory
 * management. If PAYMENT_GATEWAY_KEY is unset we fall back to a mock
 * gateway suitable for local development and tests; swapping in Paystack
 * (or any other gateway) only requires changing the branch below and never
 * touches CheckoutService/OrderService.
 */
class PaymentService
{
    public function initiate(Order $order, string $method = 'mock'): Transaction
    {
        return Transaction::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'amount' => $order->grand_total,
            'currency' => $order->currency,
            'payment_method' => $method,
            'status' => Transaction::STATUS_PENDING,
        ]);
    }

    /**
     * Charge the transaction. Returns the updated transaction.
     *
     * @throws PaymentFailedException
     */
    public function charge(Transaction $transaction): Transaction
    {
        $gatewayKey = config('services.payment.key');

        $result = $gatewayKey
            ? $this->chargeWithPaystack($transaction)
            : $this->chargeWithMockGateway($transaction);

        $transaction->update([
            'status' => $result['status'],
            'gateway_reference' => $result['reference'],
            'gateway_response' => $result['raw'],
        ]);

        Payment::create([
            'transaction_id' => $transaction->id,
            'gateway' => $result['gateway'],
            'gateway_reference' => $result['reference'],
            'amount' => $transaction->amount,
            'status' => $result['status'],
            'raw_payload' => $result['raw'],
        ]);

        if ($result['status'] !== Transaction::STATUS_SUCCESSFUL) {
            throw new PaymentFailedException('Payment was not successful.');
        }

        return $transaction->fresh();
    }

    private function chargeWithMockGateway(Transaction $transaction): array
    {
        // Deterministic success in local/dev/test so checkout/order flows are testable.
        return [
            'gateway' => 'mock',
            'reference' => 'MOCK-'.strtoupper(Str::random(10)),
            'status' => Transaction::STATUS_SUCCESSFUL,
            'raw' => ['message' => 'Mock gateway: payment auto-approved for development.'],
        ];
    }

    private function chargeWithPaystack(Transaction $transaction): array
    {
        $baseUrl = config('services.payment.base_url', 'https://api.paystack.co');
        $secret = config('services.payment.secret');

        try {
            $response = Http::withToken($secret)
                ->post("{$baseUrl}/transaction/initialize", [
                    'email' => $transaction->user->email,
                    'amount' => (int) round($transaction->amount * 100),
                    'reference' => $transaction->reference,
                    'currency' => $transaction->currency,
                ]);

            $data = $response->json();

            return [
                'gateway' => 'paystack',
                'reference' => $data['data']['reference'] ?? $transaction->reference,
                'status' => $response->successful() ? Transaction::STATUS_PENDING : Transaction::STATUS_FAILED,
                'raw' => $data,
            ];
        } catch (\Throwable $e) {
            return [
                'gateway' => 'paystack',
                'reference' => $transaction->reference,
                'status' => Transaction::STATUS_FAILED,
                'raw' => ['error' => $e->getMessage()],
            ];
        }
    }

    public function refund(Transaction $transaction): Transaction
    {
        $transaction->update(['status' => Transaction::STATUS_REFUNDED]);

        Payment::create([
            'transaction_id' => $transaction->id,
            'gateway' => $transaction->payment_method,
            'gateway_reference' => $transaction->gateway_reference,
            'amount' => $transaction->amount,
            'status' => Transaction::STATUS_REFUNDED,
            'raw_payload' => ['message' => 'Refund processed'],
        ]);

        return $transaction->fresh();
    }
}
