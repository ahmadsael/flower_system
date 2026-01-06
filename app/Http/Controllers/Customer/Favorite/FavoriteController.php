<?php

namespace App\Http\Controllers\Customer\Favorite;

use App\Http\Controllers\Controller;
use App\Models\CustomerFavorite;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(): View
    {
        $customer = Auth::guard('customer')->user();

        $favorites = $customer->favorites()
            ->where('status', 'active')
            ->with('colors')
            ->get();

        return view('Customer.Favorite.index', [
            'favorites' => $favorites,
        ]);
    }

    public function toggle(Product $product): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();

        $existing = CustomerFavorite::query()
            ->where('customer_id', $customer->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return back()->with('success_message', 'Removed from favorites.');
        }

        CustomerFavorite::query()->create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
        ]);

        return back()->with('success_message', 'Added to favorites.');
    }
}
