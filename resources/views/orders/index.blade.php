@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
    <h1 class="text-3xl font-bold mb-6">My Orders</h1>

    @if($orders->count())
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-sm text-gray-600">Order ID</p>
                            <p class="text-lg font-bold">#{{ $order->id }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Order Date</p>
                            <p class="text-lg">{{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="text-lg font-semibold">
                                <span class="px-3 py-1 rounded text-sm
                                    {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ !in_array($order->status, ['delivered', 'cancelled']) ? 'bg-blue-100 text-blue-800' : '' }}
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Amount</p>
                            <p class="text-lg font-bold text-blue-600">Rs. {{ number_format($order->total_price, 2) }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t flex justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Items: {{ $order->items->count() }}</p>
                            <p class="text-sm text-gray-600">Payment: {{ ucfirst($order->payment_status) }}</p>
                        </div>
                        <a href="/orders/{{ $order->id }}" class="text-blue-600 hover:underline">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <p class="text-gray-600 text-lg mb-4">No orders yet</p>
            <a href="/products" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
                Start Shopping
            </a>
        </div>
    @endif
@endsection
