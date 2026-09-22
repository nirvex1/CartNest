@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-center">Create Account</h1>

    <form method="POST" action="/register">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Name</label>
            <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded" value="{{ old('name') }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Gender</label>
            <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                <option value="">Select Gender</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Email</label>
            <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded" value="{{ old('email') }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Password</label>
            <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Phone Number</label>
            <input type="text" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded" value="{{ old('phone') }}" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700">
            Register
        </button>

        <p class="text-center mt-4 text-gray-600">
            Already have an account? <a href="/login" class="text-blue-600 hover:underline">Login</a>
        </p>
    </form>
@endsection
