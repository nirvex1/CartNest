@extends('admin.layout')

@section('title', 'Manage Orders')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Orders</h1>
    </div>

    @if($orders->count())
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left">Order ID</th>
                        <th class="px-6 py-3 text-left">Customer</th>
                        <th class="px-6 py-3 text-left">Total</th>
                        <th class="px-6 py-3 text-left">Payment</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-3">#{{ $order->id }}</td>
                            <td class="px-6 py-3">{{ $order->user->name }}</td>
                            <td class="px-6 py-3">Rs. {{ number_format($order->total_price, 2) }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-1 rounded text-sm {{ $order->payment_status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-1 rounded text-sm
                                    {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ !in_array($order->status, ['delivered', 'cancelled']) ? 'bg-blue-100 text-blue-800' : '' }}
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-3">
                                <a href="/admin/orders/{{ $order->id }}" class="text-blue-600 hover:underline">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-600">No orders found</p>
        </div>
    @endif
@endsection
