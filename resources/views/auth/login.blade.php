@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-center">Login</h1>

    <form method="POST" action="/login">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Email</label>
            <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded" value="{{ old('email') }}" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Password</label>
            <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700">
            Login
        </button>

        <div class="text-center mt-4">
            <a href="/password/forgot" class="text-blue-600 hover:underline">Forgot your password?</a>
        </div>

        <p class="text-center mt-4 text-gray-600">
            Don't have an account? <a href="/register" class="text-blue-600 hover:underline">Register</a>
        </p>
    </form>
@endsection
