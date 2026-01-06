<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Your favorite bouquets.">

    <title>My Favorites | Bloom & Blossom</title>

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
                            <li class="current-list-item"><a href="#">Favorites</a></li>
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

<div class="product-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="section-title">
                    <h3><span class="orange-text">My</span> Favorites</h3>
                    <p>Your saved bouquets in one place.</p>
                </div>
            </div>
        </div>

        @if (session('success_message'))
            <div class="alert alert-success">
                {{ session('success_message') }}
            </div>
        @endif

        <div class="row">
            @if($favorites->isEmpty())
                <div class="col-12 text-center">
                    <p>You have no favorites yet. <a href="{{ route('home') }}#products">Browse bouquets.</a></p>
                </div>
            @else
                @foreach($favorites as $product)
                    <div class="col-lg-4 col-md-6 text-center mb-4">
                        <div class="single-product-item h-100 d-flex flex-column shadow-sm rounded">
                            <div class="product-image">
                                @php
                                    $imagePath = $product->image
                                        ? asset('storage/' . $product->image)
                                        : asset('web_assets/assets/img/products/product-img-1.jpg');
                                @endphp
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ $imagePath }}" alt="{{ $product->name }}" style="height:260px; object-fit:cover;">
                                </a>
                            </div>
                            <h3 class="mt-3">{{ $product->name }}</h3>
                            <p class="product-price">
                                <span>Per bouquet</span> ${{ number_format($product->price, 2) }}
                            </p>
                            <div class="mb-2">
                                @foreach($product->colors as $color)
                                    <span class="badge" title="{{ $color->name }}"
                                          style="background-color: {{ $color->hex_code }}; border:1px solid #ccc;">
                                        &nbsp;
                                    </span>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-between gap-2 mt-auto">
                                <form action="{{ route('customer.cart.add', $product->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="cart-btn w-100">
                                        <i class="fas fa-shopping-basket"></i> Add
                                    </button>
                                </form>
                                <form action="{{ route('customer.favorites.toggle', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-heart-broken"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<script src="{{ asset('web_assets/assets/js/jquery-1.11.3.min.js') }}"></script>
<script src="{{ asset('web_assets/assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('web_assets/assets/js/main.js') }}"></script>

</body>
</html>


