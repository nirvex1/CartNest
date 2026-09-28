@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="grid grid-cols-2 gap-8">
        <div>
            <div class="h-96 bg-gray-300 rounded flex items-center justify-center">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-gray-600">No Image</span>
                @endif
            </div>
        </div>

        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
            <p class="text-gray-600 mb-4">Category: <strong>{{ $product->category->name }}</strong></p>
            <p class="text-gray-700 mb-6">{{ $product->description }}</p>

            <div class="mb-6">
                <span class="text-4xl font-bold text-blue-600">Rs. {{ number_format($product->price, 2) }}</span>
            </div>

            <div class="mb-6">
                <span class="text-lg {{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                </span>
            </div>

            @auth
                @if($product->stock_quantity > 0)
                    <form method="POST" action="{{ route('cart.add', $product->id) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Quantity</label>
                            <input type="number" name="quantity" min="1" max="{{ $product->stock_quantity }}" value="1" class="border border-gray-300 rounded px-3 py-2 w-32">
                        </div>
                        <button type="submit" class="block w-full rounded-xl bg-[#1f4935] px-8 py-3 text-lg font-semibold text-white transition hover:bg-[#163827]">
                            Add to Cart
                        </button>
                    </form>
                    <form method="POST" action="{{ route('cart.buy-now', $product->id) }}" class="mt-3">
                        @csrf
                        <div class="mb-3 flex items-center justify-between gap-4">
                            <label for="buy-now-quantity" class="text-sm font-semibold text-[#6b746f]">Quantity</label>
                            <input id="buy-now-quantity" type="number" name="quantity" min="1" max="{{ $product->stock_quantity }}" value="1" class="w-24 rounded-lg border border-[#d7e1d9] px-3 py-2 text-center">
                        </div>
                        <button type="submit" class="block w-full rounded-xl border-2 border-[#e4795c] px-8 py-3 text-lg font-semibold text-[#c85f45] transition hover:bg-[#fff4f0]">
                            Buy Now
                        </button>
                    </form>
                @endif
            @else
                <p class="text-gray-600"><a href="/login" class="text-blue-600 hover:underline">Login</a> to add products to cart</p>
            @endauth

            <div class="mt-6">
                <a href="/products" class="text-blue-600 hover:underline">← Back to Products</a>
            </div>
        </div>
    </div>
@endsection
