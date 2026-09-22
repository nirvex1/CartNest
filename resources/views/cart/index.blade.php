@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Shopping Cart</h1>

    @if($cartItems->count())
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-2">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left">Product</th>
                                <th class="px-6 py-3 text-left">Price</th>
                                <th class="px-6 py-3 text-left">Quantity</th>
                                <th class="px-6 py-3 text-left">Subtotal</th>
                                <th class="px-6 py-3 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $item->product->name }}</td>
                                    <td class="px-6 py-4">Rs. {{ number_format($item->product->price, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="/cart/{{ $item->id }}" class="inline-flex gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" min="1" value="{{ $item->quantity }}" class="border border-gray-300 rounded px-2 py-1 w-16">
                                            <button type="submit" class="bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700 text-sm">Update</button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 font-semibold">Rs. {{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="/cart/{{ $item->id }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold mb-4">Order Summary</h3>
                    <div class="mb-4 pb-4 border-b">
                        <div class="flex justify-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rs. {{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping:</span>
                            <span>Free</span>
                        </div>
                    </div>
                    <div class="flex justify-between text-lg font-bold mb-6">
                        <span>Total:</span>
                        <span>Rs. {{ number_format($total, 2) }}</span>
                    </div>
                    <a href="/checkout" class="block w-full bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700">
                        Proceed to Checkout
                    </a>
                    <a href="/products" class="block w-full text-center mt-2 text-blue-600 hover:underline">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <p class="text-gray-600 text-lg mb-4">Your cart is empty</p>
            <a href="/products" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
                Continue Shopping
            </a>
        </div>
    @endif
@endsection
