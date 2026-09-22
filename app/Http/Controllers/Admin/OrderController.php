<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
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
        /** @noinspection PhpUndefinedFieldInspection */
        $orders = Order::query()->with('user')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $items = $order->items()->with('product')->get();

        return view('admin.orders.show', compact('order', 'items'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update($validated);

        return back()->with('success', 'Order status updated!');
    }
}
