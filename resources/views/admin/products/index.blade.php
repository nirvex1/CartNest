@extends('admin.layout')

@section('title', 'Manage Products')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Products</h1>
        <a href="/admin/products/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Add New Product
        </a>
    </div>

    @if($products->count())
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Category</th>
                        <th class="px-6 py-3 text-left">Price</th>
                        <th class="px-6 py-3 text-left">Stock</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $product->name }}</td>
                            <td class="px-6 py-3">{{ $product->category->name }}</td>
                            <td class="px-6 py-3">Rs. {{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-3">{{ $product->stock_quantity }}</td>
                            <td class="px-6 py-3">
                                <a href="/admin/products/{{ $product->id }}/edit" class="text-blue-600 hover:underline mr-4">Edit</a>
                                <form method="POST" action="/admin/products/{{ $product->id }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-600">No products found</p>
        </div>
    @endif
@endsection
