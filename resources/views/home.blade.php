@extends('layouts.app')

@section('title', 'Home - CartNest')

@section('content')
<div class="header bg-orange-500 text-Blue py-4">
    <div class="container mx-auto flex justify-between items-center">
        <div class="logo text-2xl font-bold">CartNest</div>
        <div class="search-bar">
            <input type="text" placeholder="Search in Store" class="px-4 py-2 rounded">
            <button class="bg-Black text-black-500 px-4 py-2 rounded">Search</button>
        </div>
        
    </div>
</div>

<div class="container mx-auto mt-8">
   
    <div class="grid grid-cols-4 gap-6">
       
        @foreach($products as $product)
          
        <div class="product-card border rounded p-3 shadow-sm">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-40 object-cover mb-3 rounded">
            <h3 class="text-base font-bold mb-2">{{ $product->name }}</h3>
            <div class="description-container">
                <p class="text-gray-600 text-sm line-clamp-2 description-text-{{ $product->id }}" id="desc-{{ $product->id }}">
                    {{ $product->description }}
                </p>
                <button class="text-orange-500 text-xs font-semibold mt-1 toggle-btn" onclick="toggleDescription({{ $product->id }})">
                    See More
                </button>
            </div>
            <p class="text-orange-500 font-bold mt-2">${{ $product->price }}</p>
            <a href="{{ route('products.show', $product->id) }}" class="bg-orange-500 text-blue px-3 py-2 rounded mt-2 inline-block text-sm">View Product</a>
        </div>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $products->links() }}
        </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-none {
    display: block;
    -webkit-line-clamp: unset;
}
</style>

<script>
function toggleDescription(productId) {
    const descElement = document.getElementById('desc-' + productId);
    const btn = event.target;
    
    if (descElement.classList.contains('line-clamp-2')) {
        descElement.classList.remove('line-clamp-2');
        descElement.classList.add('line-clamp-none');
        btn.textContent = 'Hide';
    } else {
        descElement.classList.add('line-clamp-2');
        descElement.classList.remove('line-clamp-none');
        btn.textContent = 'See More';
    }
}
</script>
@endsection
