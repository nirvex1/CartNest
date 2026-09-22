@extends('admin.layout')

@section('title', 'Order Details')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Order #{{ $order->id }}</h1>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <div class="col-span-2">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Order Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Customer</p>
                        <p class="font-semibold">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Email</p>
                        <p class="font-semibold">{{ $order->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Order Date</p>
                        <p class="font-semibold">{{ $order->created_at->format('F d, Y H:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Payment Status</p>
                        <p class="font-semibold">{{ ucfirst($order->payment_status) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Items</h2>
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Product</th>
                            <th class="px-4 py-2 text-left">Qty</th>
                            <th class="px-4 py-2 text-left">Price</th>
                            <th class="px-4 py-2 text-left">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $item->product->name }}</td>
                                <td class="px-4 py-2">{{ $item->quantity }}</td>
                                <td class="px-4 py-2">Rs. {{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-4 py-2">Rs. {{ number_format($item->unit_price * $item->quantity, 2) }}</td>
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
                <h3 class="text-xl font-bold mb-4">Update Status</h3>

                <form method="POST" action="/admin/orders/{{ $order->id }}/status">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Order Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                        Update Status
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t">
                    <h3 class="font-bold mb-3">Order Total</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>Rs. {{ number_format($order->total_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-blue-600 pt-2 border-t">
                            <span>Total:</span>
                            <span>Rs. {{ number_format($order->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="/admin/orders" class="block text-center text-blue-600 hover:underline">
                        ← Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
