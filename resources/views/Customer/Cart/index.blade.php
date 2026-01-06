<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Your Bloom & Blossom cart.">

    <title>My Cart | Bloom & Blossom</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('web_assets/assets/img/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('web_assets/assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web_assets/assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web_assets/assets/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('web_assets/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('web_assets/assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('web_assets/assets/css/meanmenu.min.css') }}">
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
                            <li class="current-list-item"><a href="#">My Cart</a></li>
                        </ul>
                    </nav>

                    <div class="d-flex align-items-center">
                        @auth('customer')
                            <span class="text-white me-3">Hi, {{ auth('customer')->user()->name }}</span>
                            <form action="{{ route('customer.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-white p-0">Logout</button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mobile-menu"></div>
</div>

<div class="cart-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Your Cart</h2>
            <a href="{{ route('home') }}#products" class="bordered-btn">Continue shopping</a>
        </div>

                @if (session('success_message'))
                    <div class="alert alert-success">
                        {{ session('success_message') }}
                    </div>
                @endif
                @if (session('error_message'))
                    <div class="alert alert-danger">
                        {{ session('error_message') }}
                    </div>
                @endif

                @if (empty($items))
                    <p>Your cart is empty. <a href="{{ route('home') }}#products">Browse bouquets</a>.</p>
                @else
                    <div class="table-responsive shadow-sm rounded">
                        <table class="table table-bordered align-middle mb-0">
                    <div class="table-responsive shadow-sm rounded">
                        <table class="table table-bordered align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Bouquet</th>
                                <th width="120">Price</th>
                                <th width="120">Quantity</th>
                                <th width="140">Total</th>
                                <th width="80"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $imagePath = $item['product']->image
                                                    ? asset('storage/' . $item['product']->image)
                                                    : asset('web_assets/assets/img/products/product-img-1.jpg');
                                            @endphp
                                            <img src="{{ $imagePath }}" alt="{{ $item['product']->name }}" style="width:60px;height:60px;object-fit:cover;" class="me-3">
                                            <div>
                                                <strong>{{ $item['product']->name }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item['product']->price, 2) }}</td>
                                    <td>
                                        <form action="{{ route('customer.cart.update', $item['product']->id) }}" method="POST" class="d-flex">
                                            @csrf
                                            <input type="number" name="quantity" min="1" max="100" value="{{ $item['quantity'] }}" class="form-control form-control-sm me-2">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Update</button>
                                        </form>
                                    </td>
                                    <td>${{ number_format($item['line_total'], 2) }}</td>
                                    <td>
                                        <form action="{{ route('customer.cart.remove', $item['product']->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">&times;</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card p-4">
                    <h4>Order Summary</h4>
                    <div class="d-flex justify-content-between mt-3">
                        <span>Subtotal</span>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>
                    <p class="mt-3 text-muted">Taxes and delivery will be calculated at checkout.</p>
                    @if (!empty($items))
                        <a href="{{ route('customer.orders.checkout') }}" class="boxed-btn w-100 mt-3 text-center d-block">
                            Proceed to Checkout
                        </a>
                    @else
                        <button class="boxed-btn w-100 mt-3" type="button" disabled>Proceed to Checkout</button>
                    @endif
                    <a href="{{ route('home') }}#products" class="bordered-btn w-100 text-center d-block mt-3">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('web_assets/assets/js/jquery-1.11.3.min.js') }}"></script>
<script src="{{ asset('web_assets/assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('web_assets/assets/js/jquery.countdown.js') }}"></script>
<script src="{{ asset('web_assets/assets/js/jquery.isotope-3.0.6.min.js') }}"></script>
<script src="{{ asset('web_assets/assets/js/waypoints.js') }}"></script>
<script src="{{ asset('web_assets/assets/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('web_assets/assets/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('web_assets/assets/js/jquery.meanmenu.min.js') }}"></script>
<script src="{{ asset('web_assets/assets/js/sticker.js') }}"></script>
<script src="{{ asset('web_assets/assets/js/main.js') }}"></script>

</body>
</html>


