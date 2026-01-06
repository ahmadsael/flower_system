<?php

namespace App\Http\Controllers\Customer\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $customer = Auth::guard('customer')->user();

        $orders = Order::query()
            ->where('customer_id', $customer->id)
            ->latest()
            ->withCount('orderDetails')
            ->get();

        return view('Customer.Order.index', [
            'orders' => $orders,
        ]);
    }

    public function show(Order $order): View
    {
        $this->authorizeOrder($order);

        $order->load(['orderDetails.product']);

        return view('Customer.Order.show', [
            'order' => $order,
        ]);
    }

    public function checkout(Request $request): View
    {
        $cart = $this->getCart($request);

        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->where('status', 'active')
            ->get()
            ->keyBy('id');

        $items = [];
        $subtotal = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product) {
                continue;
            }

            $lineTotal = $product->price * $quantity;
            $subtotal += $lineTotal;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'line_total' => $lineTotal,
            ];
        }

        $tax = 0;
        $discount = 0;
        $total = $subtotal + $tax - $discount;

        return view('Customer.Order.checkout', [
            'items' => $items,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
        ]);
    }

    public function place(Request $request): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();
        $cart = $this->getCart($request);

        if (empty($cart)) {
            return redirect()
                ->route('customer.cart.index')
                ->with('error_message', 'Your cart is empty.');
        }

        $data = $request->validate([
            'shipping_address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
        ]);

        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->where('status', 'active')
            ->get()
            ->keyBy('id');

        $subtotal = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product) {
                continue;
            }

            $subtotal += $product->price * $quantity;
        }

        if ($subtotal <= 0) {
            return redirect()
                ->route('customer.cart.index')
                ->with('error_message', 'Unable to place order with current cart.');
        }

        $tax = 0;
        $discount = 0;
        $total = $subtotal + $tax - $discount;

        $order = Order::query()->create([
            'customer_id' => $customer->id,
            'order_number' => 'ORD-'.Str::upper(Str::random(8)),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
            'status' => 'pending',
            'payment_method' => 'cash',
            'payment_status' => 'unpaid',
            'shipping_address' => $data['shipping_address'],
            'phone' => $data['phone'],
        ]);

        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product) {
                continue;
            }

            OrderDetails::query()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_color_id' => null,
                'color_name' => null,
                'color_hex' => null,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'total' => $product->price * $quantity,
            ]);
        }

        $this->putCart($request, []);

        return redirect()
            ->route('customer.orders.show', $order->id)
            ->with('success_message', 'Your order has been placed successfully.');
    }

    protected function authorizeOrder(Order $order): void
    {
        $customer = Auth::guard('customer')->user();

        if ($order->customer_id !== $customer->id) {
            abort(403);
        }
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
