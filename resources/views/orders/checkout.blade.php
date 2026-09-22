@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="grid grid-cols-3 gap-6">
        <div class="col-span-2">
            <h1 class="text-3xl font-bold mb-6">Checkout</h1>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Shipping Address</h2>
                <form method="POST" action="/order">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Full Address</label>
                        <textarea name="shipping_address" rows="4" class="w-full border border-gray-300 rounded px-3 py-2" required></textarea>
                    </div>

                    <h2 class="text-xl font-bold mb-4 mt-6">Order Items</h2>
                    <table class="w-full mb-6">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Product</th>
                                <th class="px-4 py-2 text-left">Qty</th>
                                <th class="px-4 py-2 text-left">Price</th>
                                <th class="px-4 py-2 text-left">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $item->product->name }}</td>
                                    <td class="px-4 py-2">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2">Rs. {{ number_format($item->product->price, 2) }}</td>
                                    <td class="px-4 py-2">Rs. {{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded hover:bg-blue-700">
                        Continue to Payment
                    </button>
                </form>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold mb-4">Order Summary</h3>
                <div class="space-y-2 pb-4 border-b">
                    @foreach($cartItems as $item)
                        <div class="flex justify-between text-sm">
                            <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                            <span>Rs. {{ number_format($item->product->price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <div class="flex justify-between mb-2">
                        <span>Subtotal:</span>
                        <span>Rs. {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-blue-600">
                        <span>Total:</span>
                        <span>Rs. {{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
