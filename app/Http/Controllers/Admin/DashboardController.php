<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Apply middleware in constructor
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->middleware('admin');
    }

    public function index(): View
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        /** @noinspection PhpUndefinedFieldInspection */
        $totalRevenue = (int) Order::query()->where('payment_status', '=', 'completed')->sum('total_price');

        // Aggregated data for the chart
        $chartLabels = Order::selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('date');

        $ordersData = Order::selectRaw('COUNT(*) as count, DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count');

        $revenueData = Order::selectRaw('SUM(total_price) as revenue, DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('revenue');

        return view('admin.dashboard', compact('totalUsers', 'totalProducts', 'totalOrders', 'totalRevenue', 'chartLabels', 'ordersData', 'revenueData'));
    }

    public function storeAd(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|string',
        ]);

        // Store ad logic here (create an Ad model if needed)
        // For now, just return success message
        return back()->with('success', 'Ad created successfully!');
    }
}
