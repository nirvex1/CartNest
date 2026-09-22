<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('process');
    }

    public function process(Order $order, Request $request): RedirectResponse|View
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:esewa,khalti,bank',
        ]);

        $paymentMethod = $validated['payment_method'];
        $order->payment_method = $paymentMethod;
        $order->save();

        if ($paymentMethod === 'esewa') {
            return $this->initiateEsewa($order);
        } elseif ($paymentMethod === 'khalti') {
            return $this->initiateKhalti($order);
        } elseif ($paymentMethod === 'bank') {
            return $this->initiateBankTransfer($order);
        }

        return back()->with('error', 'Selected payment method is currently unsupported.');
    }

    private function initiateEsewa(Order $order): View
    {
        $transactionUuid = 'ORD-' . $order->id . '-' . time();
        
        $totalAmount = $order->total_price;
        
        $productCode = config('services.esewa.merchant_code', 'EPAYTEST');
        $secretKey = config('services.esewa.secret_key', '8gBm/:&EnhH.1/q');

        $order->transaction_uuid = $transactionUuid;
        $order->save();

        $successUrl = route('payment.esewa.verify');
        $failureUrl = route('orders.show', $order->id);

        $message = "total_amount={$totalAmount},transaction_uuid={$transactionUuid},product_code={$productCode}";
        $signature = base64_encode(hash_hmac('sha256', $message, $secretKey, true));

        $formData = [
            'amount' => $totalAmount,
            'tax_amount' => 0,
            'total_amount' => $totalAmount,
            'transaction_uuid' => $transactionUuid,
            'product_code' => $productCode,
            'product_service_charge' => 0,
            'product_delivery_charge' => 0,
            'success_url' => $successUrl,
            'failure_url' => $failureUrl,
            'signed_field_names' => 'total_amount,transaction_uuid,product_code',
            'signature' => $signature,
        ];

        $actionUrl = config('services.esewa.base_url', 'https://rc-epay.esewa.com.np') . '/api/epay/main/v2/form';

        // Return an auto-submitting view to fix the eSewa 404/GET method issue
        return view('payments.esewa-redirect', compact('actionUrl', 'formData'));
    }

    private function initiateKhalti(Order $order): RedirectResponse
    {
        $totalAmount = $order->total_price;

        $response = Http::withHeaders([
            'Authorization' => 'Key ' . config('services.khalti.secret_key'),
            'Content-Type' => 'application/json',
        ])->post(rtrim(config('services.khalti.base_url', 'https://a.khalti.com'), '/') . '/api/v2/epayment/initiate/', [
            'return_url' => route('payment.khalti.verify'),
            'website_url' => config('app.url'),
            'amount' => (int) ($totalAmount * 100), // Converted to Paisa
            'purchase_order_id' => (string) $order->id,
            'purchase_order_name' => 'Order #' . $order->id,
        ]);

        if (!$response->successful()) {
            return back()->with('error', 'Could not initiate Khalti payment. Response: ' . $response->body());
        }

        $responseData = $response->json();
        
        $order->transaction_uuid = $responseData['pidx'];
        $order->save();

        return redirect()->away($responseData['payment_url']);
    }

    private function initiateBankTransfer(Order $order): RedirectResponse
    {
        // Implement manual bank transfer handling logic or ConnectIPS integration here
        $order->payment_status = 'pending';
        $order->save();

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Bank transfer instructions saved. Please complete the bank transfer.');
    }

    public function verifyEsewa(Request $request): RedirectResponse
    {
        $encodedData = $request->query('data');
        if (!$encodedData) {
            return redirect()->route('home')->with('error', 'Invalid payment response.');
        }

        $decodedData = json_decode(base64_decode($encodedData), true);
        $transactionUuid = $decodedData['transaction_uuid'] ?? null;
        
        $order = Order::where('transaction_uuid', $transactionUuid)->firstOrFail();

        $response = Http::get(rtrim(config('services.esewa.base_url', 'https://rc-epay.esewa.com.np'), '/') . '/api/epay/transaction/status/', [
            'product_code' => config('services.esewa.merchant_code', 'EPAYTEST'),
            'total_amount' => $decodedData['total_amount'],
            'transaction_uuid' => $transactionUuid,
        ]);

        if ($response->successful() && data_get($response->json(), 'status') === 'COMPLETE') {
            $order->payment_status = 'completed';
            $order->status = 'processing';
            $order->save();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'eSewa payment successful!');
        }

        return redirect()->route('orders.show', $order->id)
            ->with('error', 'Payment verification failed.');
    }

    public function verifyKhalti(Request $request): RedirectResponse
    {
        $pidx = $request->query('pidx');
        $order = Order::where('transaction_uuid', $pidx)->firstOrFail();

        $response = Http::withHeaders([
            'Authorization' => 'Key ' . config('services.khalti.secret_key'),
        ])->post(rtrim(config('services.khalti.base_url', 'https://a.khalti.com'), '/') . '/api/v2/epayment/lookup/', [
            'pidx' => $pidx,
        ]);

        if ($response->successful() && data_get($response->json(), 'status') === 'Completed') {
            $order->payment_status = 'completed';
            $order->status = 'processing';
            $order->save();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Khalti payment successful!');
        }

        return redirect()->route('orders.show', $order->id)
            ->with('error', 'Khalti payment verification failed or was canceled.');
    }
}