<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CartNest')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-blue-600">CartNest</a>
                </div>

                <div class="flex space-x-6">
                    <a href="/products" class="bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700">Products</a>

                    @auth
                        <a href="/cart" class="bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700">Cart</a>
                        <a href="/orders" class="bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700">Orders</a>

                        @if(auth()->user()->is_admin)
                            <a href="/admin" class="bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700">Admin</a>
                        @endif

                        <form method="POST" action="/logout" style="display: inline;">
                            @csrf
                            <button type="submit" style="background-color: #dc2626; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 600; border: none; cursor: pointer;" onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">Logout</button>
                        </form>
                    @else
                        <a href="/login" style="background-color: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 600; display: inline-block; text-decoration: none;" onmouseover="this.style.backgroundColor='#1d4ed8'" onmouseout="this.style.backgroundColor='#2563eb'">Login</a>
                        <a href="/register" style="background-color: #16a34a; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 600; display: inline-block; text-decoration: none;" onmouseover="this.style.backgroundColor='#15803d'" onmouseout="this.style.backgroundColor='#16a34a'">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
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

    <footer class="bg-gray-200 text-gray-600 text-center py-4 mt-8">
        <p>&copy; 2026 CartNest. All rights reserved.</p>
    </footer>
</body>
</html>
