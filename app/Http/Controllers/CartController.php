<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();
        $total = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Product $product, Request $request): RedirectResponse
    {
        $quantity = $request->input('quantity', 1);

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function remove(CartItem $cartItem): RedirectResponse
    {
        if ($cartItem->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }

        $cartItem->delete();

        return back()->with('success', 'Product removed from cart!');
    }

    public function update(CartItem $cartItem, Request $request): RedirectResponse
    {
        if ($cartItem->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }

        $quantity = $request->input('quantity', 1);
        $cartItem->quantity = max(1, $quantity);
        $cartItem->save();

        return back()->with('success', 'Cart updated!');
    }
}

