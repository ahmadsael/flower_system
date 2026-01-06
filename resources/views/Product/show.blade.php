<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Bouquet details.">

    <title>{{ $product->name }} | Bloom & Blossom</title>

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
                            <li class="current-list-item"><a href="#">Bouquet Details</a></li>
                        </ul>
                    </nav>

                    <div class="d-flex align-items-center">
                        @auth('customer')
                            <span class="text-white me-3">Hi, {{ auth('customer')->user()->name }}</span>
                            <a class="shopping-cart me-3" href="{{ route('customer.cart.index') }}"><i class="fas fa-shopping-cart"></i></a>
                            <a class="text-white" href="{{ route('customer.favorites.index') }}"><i class="fas fa-heart"></i></a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mobile-menu"></div>
</div>

<div class="single-product mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                @php
                    $imagePath = $product->image
                        ? asset('storage/' . $product->image)
                        : asset('web_assets/assets/img/products/product-img-1.jpg');
                @endphp
                <img src="{{ $imagePath }}" alt="{{ $product->name }}" class="img-fluid rounded shadow-sm">
            </div>
            <div class="col-md-7">
                <h3 class="mb-2">{{ $product->name }}</h3>
                <p class="product-price mb-3">
                    <span>Per bouquet</span> ${{ number_format($product->price, 2) }}
                </p>
                <p class="text-muted">
                    {{ $product->description ?? 'Fresh seasonal bouquet crafted by our farmers.' }}
                </p>

                <div class="mb-3">
                    <strong>Availability:</strong>
                    @if($product->status === 'active' && $product->stock > 0)
                        <span class="badge bg-success ms-2">In stock ({{ $product->stock }})</span>
                    @else
                        <span class="badge bg-danger ms-2">Out of stock</span>
                    @endif
                </div>

                <div class="mb-3">
                    <strong>Colors:</strong>
                    @if($product->colors->isEmpty())
                        <span class="text-muted ms-2">Standard mix</span>
                    @else
                        <span class="ms-2 d-inline-flex gap-2">
                            @foreach($product->colors as $color)
                                <span class="badge"
                                      title="{{ $color->name }} ({{ $color->stock }} in stock)"
                                      style="background-color: {{ $color->hex_code }}; border:1px solid #ccc;">
                                    &nbsp;
                                </span>
                            @endforeach
                        </span>
                    @endif
                </div>

                <div class="mt-4 d-flex align-items-center gap-3 flex-wrap">
                    @auth('customer')
                        <form action="{{ route('customer.cart.add', $product->id) }}" method="POST" class="d-flex align-items-center gap-2">
                            @csrf
                            <input type="number" name="quantity" min="1" max="100" value="1" class="form-control w-auto">
                            <button type="submit" class="cart-btn">
                                <i class="fas fa-shopping-basket"></i> Add to Cart
                            </button>
                        </form>
                        <form action="{{ route('customer.favorites.toggle', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-heart"></i> Favorite
                            </button>
                        </form>
                    @else
                        <a href="{{ route('home', ['section' => 'login']) }}#auth" class="cart-btn">
                            <i class="fas fa-user"></i> Login to order
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('web_assets/assets/js/jquery-1.11.3.min.js') }}"></script>
<script src="{{ asset('web_assets/assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('web_assets/assets/js/main.js') }}"></script>

</body>
</html>


