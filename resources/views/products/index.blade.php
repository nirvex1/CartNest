@extends('layouts.app')

@section('title', 'All products - CartNest')

@section('content')
    <section class="reveal flex flex-col justify-between gap-5 border-b border-[#e4e9e5] pb-8 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#e4795c]">The full edit</p>
            <h1 class="mt-2 text-4xl font-bold tracking-[-0.05em] text-[#17221d]">All products</h1>
            <p class="mt-2 text-[#6b746f]">Good things, thoughtfully gathered.</p>
        </div>
        <p class="text-sm text-[#6b746f]">{{ $products->total() }} pieces to explore</p>
    </section>

    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-[220px_1fr]">
        <aside class="reveal reveal-delay-1">
            <form method="GET" action="{{ route('products.index') }}" class="rounded-3xl border border-[#e4e9e5] bg-white p-5">
                <h2 class="font-display text-lg font-bold text-[#17221d]">Refine your search</h2>
                <label class="mt-5 block text-xs font-bold uppercase tracking-wider text-[#6b746f]" for="category_id">Category</label>
                <select id="category_id" name="category_id" class="mt-2 w-full rounded-xl border border-[#d7e1d9] bg-[#fbfcfa] px-3 py-2.5 text-sm text-[#17221d] outline-none focus:border-[#1f4935]">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <label class="mt-4 block text-xs font-bold uppercase tracking-wider text-[#6b746f]" for="search">Keyword</label>
                <input id="search" type="text" name="search" placeholder="Search products..." value="{{ request('search') }}" class="mt-2 w-full rounded-xl border border-[#d7e1d9] bg-[#fbfcfa] px-3 py-2.5 text-sm outline-none focus:border-[#1f4935]">
                <button type="submit" class="button-lift mt-5 w-full rounded-xl bg-[#1f4935] py-3 text-sm font-bold text-white">Apply filters</button>
            </form>
        </aside>

        <div class="reveal reveal-delay-2">
            @if($products->count())
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($products as $product)
                        <article class="product-card overflow-hidden rounded-3xl border border-[#e4e9e5] bg-white">
                            <a href="{{ route('products.show', $product->id) }}" class="block overflow-hidden bg-[#f1f4f0]">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image aspect-[4/3] w-full object-cover">
                                @else
                                    <div class="flex aspect-[4/3] items-center justify-center text-sm text-[#8c9790]">No image available</div>
                                @endif
                            </a>
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="font-display text-lg font-bold leading-tight text-[#17221d]">{{ $product->name }}</h3>
                                    <span class="whitespace-nowrap text-base font-bold text-[#e4795c]">Rs. {{ number_format($product->price, 2) }}</span>
                                </div>
                                <p class="mt-2 line-clamp-2 text-sm leading-6 text-[#6b746f]">{{ $product->description }}</p>
                                <a href="{{ route('products.show', $product->id) }}" class="button-lift mt-5 inline-flex items-center gap-2 rounded-full bg-[#edf3ed] px-4 py-2.5 text-sm font-bold text-[#1f4935]">View details <span aria-hidden="true">→</span></a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $products->withQueryString()->links() }}
                </div>
            @else
                <div class="rounded-3xl border border-dashed border-[#cbd9ce] bg-white px-6 py-16 text-center">
                    <p class="font-display text-2xl font-bold text-[#1f4935]">Nothing here yet</p>
                    <p class="mt-2 text-[#6b746f]">Try another search or clear your filters.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
