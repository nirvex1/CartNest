<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
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

    public function checkout(): View|RedirectResponse
    {
        $cartItems = $this->purchaseItems();
        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Your cart is empty!');
        }

        $total = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

        // Return checkout page showing cart items for review
        return view('orders.checkout', compact('cartItems', 'total'));
    }

    public function create(Request $request): RedirectResponse
    {
        $cartItems = $this->purchaseItems();
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

        if (session()->has('buy_now')) {
            session()->forget('buy_now');
        } else {
            Auth::user()->cartItems()->delete();
        }

        // Redirect to payment page
        return redirect("/orders/{$order->id}/payment")->with('success', 'Proceed to payment!');
    }

    private function purchaseItems(): Collection
    {
        $buyNow = session('buy_now');

        if ($buyNow) {
            $product = Product::find($buyNow['product_id']);

            if (!$product || $product->stock_quantity < $buyNow['quantity']) {
                session()->forget('buy_now');
                return new Collection();
            }

            $item = new CartItem([
                'product_id' => $product->id,
                'quantity' => $buyNow['quantity'],
            ]);
            $item->setRelation('product', $product);

            return new Collection([$item]);
        }

        return Auth::user()->cartItems()->with('product')->get();
    }

    public function payment(Order $order): View|RedirectResponse
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

    public function show(Order $order): View|RedirectResponse
    {
        if ($order->user_id !== Auth::id()) {
            return redirect('/orders')->with('error', 'Unauthorized');
        }

        $items = $order->items()->with('product')->get();

        return view('orders.show', compact('order', 'items'));
    }
}

