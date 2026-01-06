<?php

namespace App\Http\Controllers\Customer\Cart;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $this->getCart($request);

        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product) {
                continue;
            }

            $lineTotal = $product->price * $quantity;
            $total += $lineTotal;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'line_total' => $lineTotal,
            ];
        }

        return view('Customer.Cart.index', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        if ($product->status !== 'active') {
            return back()->with('error_message', 'This bouquet is not available right now.');
        }

        $data = $request->validate([
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $quantity = (int) ($data['quantity'] ?? 1);

        $cart = $this->getCart($request);
        $cart[$product->id] = ($cart[$product->id] ?? 0) + $quantity;

        $this->putCart($request, $cart);

        return redirect()
            ->route('customer.cart.index')
            ->with('success_message', 'Bouquet added to your cart.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $cart = $this->getCart($request);

        if (! array_key_exists($product->id, $cart)) {
            return redirect()->route('customer.cart.index');
        }

        $cart[$product->id] = (int) $data['quantity'];

        $this->putCart($request, $cart);

        return redirect()
            ->route('customer.cart.index')
            ->with('success_message', 'Cart updated.');
    }

    public function remove(Request $request, Product $product): RedirectResponse
    {
        $cart = $this->getCart($request);

        unset($cart[$product->id]);

        $this->putCart($request, $cart);

        return redirect()
            ->route('customer.cart.index')
            ->with('success_message', 'Item removed from cart.');
    }

    /**
     * @return array<int,int>
     */
    protected function getCart(Request $request): array
    {
        /** @var array<int,int> $cart */
        $cart = $request->session()->get('customer_cart', []);

        return $cart;
    }

    /**
     * @param  array<int,int>  $cart
     */
    protected function putCart(Request $request, array $cart): void
    {
        $request->session()->put('customer_cart', $cart);
    }
}
