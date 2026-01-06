<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->with(['categories'])
            ->where('status', 'active')
            ->latest()
            ->limit(9)
            ->get();

        return view('welcome', [
            'products' => $products,
            'authSection' => $request->query('section'),
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['colors']);

        return view('Product.show', [
            'product' => $product,
        ]);
    }
}
