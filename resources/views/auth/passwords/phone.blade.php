@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-center">Reset Password</h1>

    <form method="POST" action="{{ route('password.phone') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Phone Number</label>
            <input type="text" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded" value="{{ old('phone') }}" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700">
            Send Reset Code
        </button>
    </form>
@endsection