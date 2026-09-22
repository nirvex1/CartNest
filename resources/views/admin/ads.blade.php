@extends('layouts.app')

@section('title', 'Admin - Post Ads')

@section('content')
<div class="container mx-auto mt-8">
    <h2 class="text-3xl font-bold mb-4">Post New Advertisement</h2>
    <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md">
        @csrf
        <div class="mb-4">
            <label for="title" class="block text-gray-700 font-bold mb-2">Ad Title</label>
            <input type="text" name="title" id="title" class="w-full border rounded px-4 py-2" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
            <textarea name="description" id="description" rows="4" class="w-full border rounded px-4 py-2" required></textarea>
        </div>

        <div class="mb-4">
            <label for="media" class="block text-gray-700 font-bold mb-2">Upload Media (Image/Video)</label>
            <input type="file" name="media" id="media" class="w-full border rounded px-4 py-2" accept="image/*,video/*" required>
        </div>

        <div class="mb-4">
            <label for="promo_code" class="block text-gray-700 font-bold mb-2">Promo Code (Optional)</label>
            <input type="text" name="promo_code" id="promo_code" class="w-full border rounded px-4 py-2">
        </div>

        <button type="submit" class="bg-orange-500 text-white px-6 py-3 rounded hover:bg-orange-600">Post Ad</button>
    </form>
</div>
@endsection