@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <h1 class="text-2xl font-bold mb-6 text-center">Reset Password</h1>
    <form method="POST" action="/password/reset">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="phone" value="{{ $phone }}">
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Phone</label>
            <input type="text" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">New Password</label>
            <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded" required>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded" required>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700">
            Reset Password
        </button>
    </form>
@endsection