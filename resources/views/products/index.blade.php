@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Products</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div>
            <form method="GET" action="/products" class="space-y-3">
                <h3 class="font-semibold">Filter by Category</h3>
                <select name="category_id" class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}" class="w-full border border-gray-300 rounded px-3 py-2">
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Search</button>
            </form>
        </div>

        <div class="md:col-span-3">
            @if($products->count())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="h-48 bg-gray-300 flex items-center justify-center">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-gray-600">No Image</span>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-lg mb-2">{{ $product->name }}</h3>
                                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description, 100) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-blue-600">Rs. {{ number_format($product->price, 2) }}</span>
                                    <a href="/products/{{ $product->id }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-600 text-lg">No products found</p>
                </div>
            @endif
        </div>
    </div>
@endsection
