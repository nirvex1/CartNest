@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
    <div class="grid grid-cols-3 gap-6">
        <div class="col-span-2">
            <h1 class="text-3xl font-bold mb-6">Order #{{ $order->id }}</h1>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Order Information</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600">Order Date</p>
                        <p class="font-semibold">{{ $order->created_at->format('F d, Y H:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Status</p>
                        <p class="font-semibold">
                            <span class="px-3 py-1 rounded text-sm
                                {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                {{ !in_array($order->status, ['delivered', 'cancelled']) ? 'bg-blue-100 text-blue-800' : '' }}
                            ">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600">Payment Status</p>
                        <p class="font-semibold">{{ ucfirst($order->payment_status) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Payment Method</p>
                        <p class="font-semibold">{{ $order->payment_method ? ucfirst($order->payment_method) : 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Items</h2>
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Product</th>
                            <th class="px-4 py-2 text-left">Quantity</th>
                            <th class="px-4 py-2 text-left">Unit Price</th>
                            <th class="px-4 py-2 text-left">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $item->product->name }}</td>
                                <td class="px-4 py-2">{{ $item->quantity }}</td>
                                <td class="px-4 py-2">Rs. {{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-4 py-2 font-semibold">Rs. {{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Shipping Address</h2>
                <p class="whitespace-pre-line text-gray-700">{{ $order->shipping_address }}</p>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold mb-4">Order Summary</h3>

                <div class="space-y-2 pb-4 border-b">
                    @foreach($items as $item)
                        <div class="flex justify-between text-sm">
                            <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                            <span>Rs. {{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <div class="flex justify-between mb-2">
                        <span>Subtotal:</span>
                        <span>Rs. {{ number_format($order->total_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between mb-4">
                        <span>Shipping:</span>
                        <span>Free</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-blue-600 pt-4 border-t">
                        <span>Total:</span>
                        <span>Rs. {{ number_format($order->total_price, 2) }}</span>
                    </div>
                </div>

                <div class="mt-6 space-y-2">
                    <a href="/orders" class="block w-full text-center text-blue-600 hover:underline">
                        ← Back to Orders
                    </a>
                    <a href="/products" class="block w-full text-center text-blue-600 hover:underline">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
