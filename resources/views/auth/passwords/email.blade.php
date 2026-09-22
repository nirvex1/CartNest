@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <h1 class="text-2xl font-bold mb-6 text-center">Forgot Password</h1>
    @if (session('status'))
        <div class="mb-4 text-green-600">{{ session('status') }}</div>
    @endif
    <form method="POST" action="/password/email">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Email</label>
            <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded" required>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700">
            Send Password Reset Link
        </button>
    </form>
@endsection