@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('content')
    <h1 class="text-3xl font-bold mb-8">Dashboard</h1>

    <div class="grid grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 font-semibold mb-2">Total Users</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $totalUsers }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 font-semibold mb-2">Total Products</h3>
            <p class="text-3xl font-bold text-green-600">{{ $totalProducts }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 font-semibold mb-2">Total Orders</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $totalOrders }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 font-semibold mb-2">Total Revenue</h3>
            <p class="text-3xl font-bold text-orange-600">Rs. {{ number_format($totalRevenue, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Quick Links</h2>
            <ul class="space-y-2">
                <li><a href="/admin/products" class="text-blue-600 hover:underline">Manage Products</a></li>
                <li><a href="/admin/products/create" class="text-blue-600 hover:underline">Add New Product</a></li>
                <li><a href="/admin/orders" class="text-blue-600 hover:underline">View Orders</a></li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Business Insights</h2>
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!}, // e.g. ['2026-04-20', '2026-04-21']
                datasets: [
                    {
                        label: 'Orders',
                        data: {!! json_encode($ordersData) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    },
                    {
                        label: 'Revenue',
                        data: {!! json_encode($revenueData) !!},
                        backgroundColor: 'rgba(255, 159, 64, 0.6)',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
@endsection
