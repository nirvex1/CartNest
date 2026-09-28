<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KhaltiPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_khalti_key_returns_actionable_error(): void
    {
        $user = User::factory()->create(['phone' => '9800000001']);
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 1500,
            'payment_status' => 'pending',
            'shipping_address' => 'Test address',
            'status' => 'pending',
        ]);

        Http::fake([
            'dev.khalti.com/*' => Http::response([
                'detail' => 'Invalid token.',
                'status_code' => 401,
            ], 401),
        ]);

        $response = $this->actingAs($user)
            ->from(route('orders.payment', $order))
            ->post(route('payment.process', $order), ['payment_method' => 'khalti']);

        $response->assertRedirect(route('orders.payment', $order));
        $response->assertSessionHas('error', 'Khalti rejected the API key. Set KHALTI_SECRET_KEY to a valid sandbox key from test-admin.khalti.com.');
    }
}