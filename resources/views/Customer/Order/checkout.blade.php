<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Checkout your Bloom & Blossom order.">

    <title>Checkout | Bloom & Blossom</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('web_assets/assets/img/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('web_assets/assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web_assets/assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web_assets/assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('web_assets/assets/css/responsive.css') }}">
</head>
<body>

<div class="top-header-area" id="sticker">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-sm-12 text-center">
                <div class="main-menu-wrap d-flex align-items-center justify-content-between">
                    <div class="site-logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('web_assets/assets/img/logo.png') }}" alt="Bloom & Blossom">
                        </a>
                    </div>

                    <nav class="main-menu d-none d-lg-block">
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('customer.cart.index') }}">Cart</a></li>
                            <li class="current-list-item"><a href="#">Checkout</a></li>
                        </ul>
                    </nav>

                    <div class="d-flex align-items-center">
                        @auth('customer')
                            <span class="text-white me-3">Hi, {{ auth('customer')->user()->name }}</span>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mobile-menu"></div>
</div>

<div class="checkout-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Order Summary</h2>
                    <a href="{{ route('customer.cart.index') }}" class="bordered-btn">Back to cart</a>
                </div>

                @if (empty($items))
                    <p>Your cart is empty. <a href="{{ route('home') }}#products">Add bouquets to continue.</a></p>
                @else
                    <ul class="list-group mb-4 shadow-sm rounded">
                        @foreach($items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $item['product']->name }}</strong>
                                    <div class="small text-muted">
                                        Qty: {{ $item['quantity'] }} × ${{ number_format($item['product']->price, 2) }}
                                    </div>
                                </div>
                                <span>${{ number_format($item['line_total'], 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="col-lg-5">
                <h2 class="mb-4">Delivery Details</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('customer.orders.place') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="mb-1">Shipping Address</label>
                        <textarea name="shipping_address" rows="3" class="form-control" required>{{ old('shipping_address') }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="mb-1">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', auth('customer')->user()->phone ?? '') }}" required>
                    </div>

                    <div class="card p-3 mt-3">
                        <div class="d-flex justify-content-between">
                            <span>Subtotal</span>
                            <strong>${{ number_format($subtotal, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tax</span>
                            <strong>${{ number_format($tax, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Discount</span>
                            <strong>-${{ number_format($discount, 2) }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span>Total</span>
                            <strong>${{ number_format($total, 2) }}</strong>
                        </div>
                    </div>

                    <button type="submit" class="boxed-btn w-100 mt-3">Place Order</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('web_assets/assets/js/jquery-1.11.3.min.js') }}"></script>
<script src="{{ asset('web_assets/assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('web_assets/assets/js/main.js') }}"></script>

</body>
</html>


