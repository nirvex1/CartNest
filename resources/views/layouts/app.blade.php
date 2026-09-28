<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CartNest')</title>
    @vite('resources/css/app.css')
</head>
<body>
    <nav class="site-nav border-b border-[#e4e9e5] bg-[#fbfcfa]/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-5 py-4 lg:px-8">
            <a href="{{ route('home') }}" class="font-display text-2xl font-bold tracking-[-0.04em] text-[#1f4935]">Cart<span class="text-[#e4795c]">Nest</span></a>

            <div class="hidden items-center gap-7 text-sm font-semibold text-[#6b746f] md:flex">
                <a href="{{ route('home') }}" class="transition hover:text-[#1f4935]">Shop</a>
                <a href="{{ route('products.index') }}" class="transition hover:text-[#1f4935]">All products</a>

                    @auth
                        <a href="{{ route('cart.index') }}" class="transition hover:text-[#1f4935]">Cart</a>
                        <a href="{{ route('orders.index') }}" class="transition hover:text-[#1f4935]">Orders</a>

                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="transition hover:text-[#1f4935]">Admin</a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="transition hover:text-[#e4795c]">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="transition hover:text-[#1f4935]">Login</a>
                        <a href="{{ route('register') }}" class="rounded-full bg-[#1f4935] px-4 py-2 text-white transition hover:bg-[#163827]">Join CartNest</a>
                    @endauth
            </div>
            <a href="{{ route('products.index') }}" class="rounded-full border border-[#cbd9ce] px-4 py-2 text-sm font-semibold text-[#1f4935] transition hover:border-[#1f4935] md:hidden">Shop</a>
            </div>
    </nav>

    <main class="mx-auto max-w-7xl px-5 py-8 lg:px-8">
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="mt-12 border-t border-[#e4e9e5] bg-[#f3f6f1]">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-5 py-8 text-sm text-[#6b746f] sm:flex-row sm:items-center sm:justify-between lg:px-8">
            <p class="font-display font-semibold text-[#1f4935]">Cart<span class="text-[#e4795c]">Nest</span></p>
            <p>&copy; 2026 CartNest. Thoughtfully made for everyday shopping.</p>
        </div>
    </footer>
</body>
</html>
