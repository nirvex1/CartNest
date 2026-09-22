<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="flex">
        <aside class="w-64 bg-gray-900 text-white h-screen p-4">
            <div class="mb-8">
                <h1 class="text-2xl font-bold">Admin Panel</h1>
            </div>

            <nav class="space-y-2">
                <a href="/admin" class="block px-4 py-2 rounded {{ Request::is('admin') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    Dashboard
                </a>
                <a href="/admin/products" class="block px-4 py-2 rounded {{ Request::is('admin/products*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    Products
                </a>
                <a href="/admin/orders" class="block px-4 py-2 rounded {{ Request::is('admin/orders*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    Orders
                </a>
                <a href="/" class="block px-4 py-2 rounded hover:bg-gray-800 mt-8">
                    Back to Store
                </a>
                <form method="POST" action="/logout" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded hover:bg-gray-800">
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <main class="flex-1">
            <div class="bg-white shadow p-6 mb-6">
                <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
            </div>

            <div class="px-6 pb-6">
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
            </div>
        </main>
    </div>
</body>
</html>
