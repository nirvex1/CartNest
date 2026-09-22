@extends('admin.layout')

@section('title', 'Edit Product')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Edit Product</h1>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="/admin/products/{{ $product->id }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Product Name</label>
                    <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('name', $product->name) }}" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full border border-gray-300 rounded px-3 py-2">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Category</label>
                        <select name="category_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Price</label>
                        <input type="number" name="price" step="0.01" min="0" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('price', $product->price) }}" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Stock Quantity</label>
                        <input type="number" name="stock_quantity" min="0" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Image URL</label>
                        <input type="text" name="image_url" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('image_url', $product->image_url) }}" placeholder="https://...">
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Update Product
                    </button>
                    <a href="/admin/products" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
