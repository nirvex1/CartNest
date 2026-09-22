<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkout(): View
    {
        // Step 1: Show items from CART (CartItem), NOT from Order
        $cartItems = Auth::user()->cartItems()->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Your cart is empty!');
        }

        $total = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

        // Return checkout page showing cart items for review
        return view('orders.checkout', compact('cartItems', 'total'));
    }

    public function create(Request $request): RedirectResponse
    {
        // Step 2: Convert cart items to order when customer confirms
        $cartItems = Auth::user()->cartItems()->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Your cart is empty!');
        }

        $validated = $request->validate([
            'shipping_address' => 'required|string',
        ]);

        $total = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

        // Create order with PENDING status (NOT paid, NOT shipped)
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $total,
            'shipping_address' => $validated['shipping_address'],
            'payment_status' => 'pending',  // Waiting for payment
            'status' => 'pending',          // Waiting for admin to process
        ]);

        // Convert each cart item to order item
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->product->price,
            ]);
        }

        // Clear the cart
        Auth::user()->cartItems()->delete();

        // Redirect to payment page
        return redirect("/orders/{$order->id}/payment")->with('success', 'Proceed to payment!');
    }

    public function payment(Order $order): View
    {
        // Step 3: Show PENDING order for payment
        if ($order->user_id !== Auth::id()) {
            return redirect('/orders')->with('error', 'Unauthorized');
        }

        // Show payment page with order details
        // At this point: payment_status = 'pending', status = 'pending'
        // Customer selects payment method and pays
        return view('orders.create', compact('order'));
    }

    public function index(): View
    {
        $orders = Auth::user()->orders()->orderBy('created_at', 'desc')->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        if ($order->user_id !== Auth::id()) {
            return redirect('/orders')->with('error', 'Unauthorized');
        }

        $items = $order->items()->with('product')->get();

        return view('orders.show', compact('order', 'items'));
    }
}

