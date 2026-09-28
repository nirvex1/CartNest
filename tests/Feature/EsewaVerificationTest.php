<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EsewaVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_connection_failure_keeps_order_pending_and_redirects_to_order(): void
    {
        $user = User::factory()->create(['phone' => '9800000001']);
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 1500,
            'payment_status' => 'pending',
            'payment_method' => 'esewa',
            'shipping_address' => 'Test address',
            'status' => 'pending',
            'transaction_uuid' => 'ORD-1-1234567890',
        ]);

        Http::fake(fn () => throw new ConnectionException('Could not resolve host'));

        $encodedData = base64_encode(json_encode([
            'transaction_uuid' => $order->transaction_uuid,
            'total_amount' => '1500.00',
        ], JSON_THROW_ON_ERROR));

        $response = $this->get(route('payment.esewa.verify', ['data' => $encodedData]));

        $response->assertRedirect(route('orders.show', $order));
        $response->assertSessionHas('error', 'Unable to reach eSewa to verify your payment. The payment is still pending; please check your order again shortly.');
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);
    }
}