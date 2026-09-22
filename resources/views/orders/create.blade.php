@extends('layouts.app')

@section('title', 'Payment')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Payment</h1>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="bg-blue-100 border border-blue-400 text-blue-800 px-4 py-3 rounded mb-6">
                <p class="font-semibold">Order Total: Rs. {{ number_format($order->total_price, 2) }}</p>
                <p class="text-sm mt-1">Order ID: #{{ $order->id }}</p>
            </div>

            <h2 class="text-xl font-bold mb-4">Select Payment Method</h2>

            <form method="POST" action="/payment/process/{{ $order->id }}" class="space-y-4">
                @csrf

                <div>
                    <label class="flex items-center p-4 border border-gray-300 rounded cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_method" value="bank" class="mr-3" checked>
                        <div>
                            <p class="font-semibold">Bank Transfer</p>
                            <p class="text-sm text-gray-600">Pay using your bank account</p>
                        </div>
                    </label>
                </div>

                <div>
                    <label class="flex items-center p-4 border border-gray-300 rounded cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_method" value="esewa" class="mr-3">
                        <div>
                            <p class="font-semibold">E-Sewa</p>
                            <p class="text-sm text-gray-600">Pay using your E-Sewa wallet</p>
                        </div>
                    </label>
                </div>

                <div>
                    <label class="flex items-center p-4 border border-gray-300 rounded cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_method" value="khalti" class="mr-3">
                        <div>
                            <p class="font-semibold">Khalti</p>
                            <p class="text-sm text-gray-600">Pay using Khalti by IME</p>
                        </div>
                    </label>
                </div>

                <div class="mt-6 pt-6 border-t">
                    <p class="text-sm text-gray-600 mb-4">This is a test/mock payment. Your order will be created for testing purposes.</p>
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded hover:bg-blue-700 font-semibold text-lg">
                        Complete Payment
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center">
            <a href="/orders" class="text-blue-600 hover:underline">← Back to Orders</a>
        </div>
    </div>
@endsection
