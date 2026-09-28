<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Client\ConnectionException;
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
            'payment_method' => 'required|in:esewa,khalti',
        ]);

        $paymentMethod = $validated['payment_method'];
        $order->payment_method = $paymentMethod;
        $order->save();

        if ($paymentMethod === 'esewa') {
            return $this->initiateEsewa($order);
        } else {
            return $this->initiateKhalti($order);
        }
    }

    private function initiateEsewa(Order $order): View|RedirectResponse
    {
        $transactionUuid = 'ORD-' . $order->id . '-' . time();
        
        $totalAmount = number_format((float) $order->total_price, 2, '.', '');
        
        $productCode = config('services.esewa.merchant_code');
        $secretKey = config('services.esewa.secret_key');
        $formUrl = config('services.esewa.form_url', 'https://rc-epay.esewa.com.np/api/epay/main/v2/form');

        if (blank($productCode) || blank($secretKey)) {
            return back()->with('error', 'eSewa is not configured. Add ESEWA_MERCHANT_CODE and ESEWA_SECRET_KEY to your .env file.');
        }

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

        $actionUrl = $formUrl;

        // Return an auto-submitting view to fix the eSewa 404/GET method issue
        return view('payments.esewa-redirect', compact('actionUrl', 'formData'));
    }

    private function initiateKhalti(Order $order): RedirectResponse
    {
        $secretKey = config('services.khalti.secret_key');

        if (blank($secretKey)) {
            return back()->with('error', 'Khalti is not configured. Add KHALTI_SECRET_KEY to your .env file.');
        }

        $totalAmount = $order->total_price;

        $response = Http::withHeaders([
            'Authorization' => 'Key ' . $secretKey,
            'Content-Type' => 'application/json',
        ])->post(rtrim(config('services.khalti.base_url', 'https://a.khalti.com'), '/') . '/api/v2/epayment/initiate/', [
            'return_url' => route('payment.khalti.verify'),
            'website_url' => config('app.url'),
            'amount' => (int) ($totalAmount * 100), // Converted to Paisa
            'purchase_order_id' => (string) $order->id,
            'purchase_order_name' => 'Order #' . $order->id,
        ]);

        if ($response->unauthorized()) {
            return back()->with('error', 'Khalti rejected the API key. Set KHALTI_SECRET_KEY to a valid sandbox key from test-admin.khalti.com.');
        }

        if (!$response->successful()) {
            return back()->with('error', 'Could not initiate Khalti payment. Response: ' . $response->body());
        }

        $responseData = $response->json();
        
        $order->transaction_uuid = $responseData['pidx'];
        $order->save();

        return redirect()->away($responseData['payment_url']);
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
        /** @var Order $order */

        try {
            $response = Http::get(rtrim(config('services.esewa.status_url', 'https://rc.esewa.com.np'), '/') . '/api/epay/transaction/status/', [
                'product_code' => config('services.esewa.merchant_code'),
                'total_amount' => $decodedData['total_amount'],
                'transaction_uuid' => $transactionUuid,
            ]);
        } catch (ConnectionException) {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Unable to reach eSewa to verify your payment. The payment is still pending; please check your order again shortly.');
        }

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
        /** @var Order $order */

        $secretKey = config('services.khalti.secret_key');
        if (blank($secretKey)) {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Khalti is not configured. Add KHALTI_SECRET_KEY to your .env file.');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Key ' . $secretKey,
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