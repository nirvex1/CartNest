@extends('layouts.app')

@section('title', 'Home - CartNest')

@section('content')
<section class="reveal relative overflow-hidden rounded-[2rem] bg-[#dceadf] px-6 py-10 sm:px-10 lg:px-14 lg:py-14">
    <div class="relative z-10 max-w-2xl">
        <p class="mb-4 text-xs font-bold uppercase tracking-[0.22em] text-[#e4795c]">A better everyday edit</p>
        <h1 class="font-display max-w-xl text-4xl font-bold leading-[1.02] tracking-[-0.05em] text-[#1f4935] sm:text-6xl">Small finds. Big difference.</h1>
        <p class="mt-5 max-w-lg text-base leading-7 text-[#526259] sm:text-lg">Thoughtfully selected essentials for the way you live, work, and unwind.</p>
        <form method="GET" action="{{ route('home') }}" class="mt-8 flex max-w-xl flex-col gap-3 sm:flex-row">
            <label class="flex min-w-0 flex-1 items-center gap-3 rounded-2xl bg-white px-4 py-3 shadow-sm ring-1 ring-[#cbd9ce] focus-within:ring-2 focus-within:ring-[#1f4935]">
                <span class="text-lg text-[#e4795c]" aria-hidden="true">⌕</span>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search your next favorite" class="min-w-0 flex-1 bg-transparent text-sm text-[#17221d] outline-none placeholder:text-[#8c9790]">
            </label>
            <button type="submit" class="button-lift rounded-2xl bg-[#1f4935] px-6 py-3 text-sm font-bold text-white">Explore collection</button>
        </form>
    </div>
    <div class="pointer-events-none absolute -right-20 -top-24 h-80 w-80 rounded-full border-[28px] border-white/40"></div>
    <div class="pointer-events-none absolute -bottom-28 right-24 h-56 w-56 rounded-full bg-[#e4795c]/20"></div>
</section>

<section class="reveal reveal-delay-1 mt-12">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#e4795c]">Browse by mood</p>
            <h2 class="mt-2 text-3xl font-bold tracking-[-0.04em] text-[#17221d]">Find your next favorite</h2>
        </div>
        <a href="{{ route('products.index') }}" class="text-sm font-bold text-[#1f4935] underline decoration-[#e4795c] decoration-2 underline-offset-4">View all products</a>
    </div>
    <div class="mt-6 flex gap-3 overflow-x-auto pb-2">
        <a href="{{ route('home') }}" class="shrink-0 rounded-full bg-[#1f4935] px-5 py-2.5 text-sm font-bold text-white">Everything</a>
        @foreach($categories as $category)
            <a href="{{ route('home', ['category_id' => $category->id]) }}" class="shrink-0 rounded-full border border-[#d7e1d9] bg-white px-5 py-2.5 text-sm font-semibold text-[#526259] transition hover:border-[#1f4935] hover:text-[#1f4935]">{{ $category->name }}</a>
        @endforeach
    </div>
</section>

<section class="reveal reveal-delay-2 mt-12">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="text-sm text-[#6b746f]">{{ $products->total() }} pieces worth discovering</p>
        </div>
        @if(request('search') || request('category_id'))
            <a href="{{ route('home') }}" class="text-sm font-semibold text-[#e4795c]">Clear filters</a>
        @endif
    </div>

    @if($products->count())
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($products as $product)
                <article class="product-card overflow-hidden rounded-3xl border border-[#e4e9e5] bg-white">
                    <a href="{{ route('products.show', $product->id) }}" class="group block overflow-hidden bg-[#f1f4f0]">
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
        <div class="mt-8">{{ $products->withQueryString()->links() }}</div>
    @else
        <div class="rounded-3xl border border-dashed border-[#cbd9ce] bg-white px-6 py-16 text-center">
            <p class="font-display text-2xl font-bold text-[#1f4935]">Nothing here yet</p>
            <p class="mt-2 text-[#6b746f]">Try another search or browse everything in the collection.</p>
        </div>
    @endif
</section>
@endsection
