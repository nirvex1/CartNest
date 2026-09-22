<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::all();
        $query = Product::query();

        $categoryId = $request->input('category_id');
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $search = $request->input('search');
        if ($search) {
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%');
        }

        $products = $query->paginate(12);

        return view('home', compact('products', 'categories'));
    }

    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }
}
