<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Order details.">

    <title>Order {{ $order->order_number }} | Bloom & Blossom</title>

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
                            <li><a href="{{ route('customer.orders.index') }}">My Orders</a></li>
                            <li class="current-list-item"><a href="#">Order Details</a></li>
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

<div class="order-details-section mt-150 mb-150">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Order {{ $order->order_number }}</h2>
            <a href="{{ route('customer.orders.index') }}" class="bordered-btn">Back to orders</a>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <h4>Items</h4>
                <div class="table-responsive mt-3 shadow-sm rounded">
                    <table class="table table-bordered align-middle mb-0">
                        <thead>
                        <tr>
                            <th>Bouquet</th>
                            <th>Color</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->orderDetails as $detail)
                            <tr>
                                <td>{{ $detail->product_name }}</td>
                                <td>
                                    @if($detail->color_name)
                                        <span class="badge" style="background-color: {{ $detail->color_hex ?? '#ccc' }};">
                                            &nbsp;
                                        </span>
                                        {{ $detail->color_name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $detail->quantity }}</td>
                                <td>${{ number_format($detail->price, 2) }}</td>
                                <td>${{ number_format($detail->total, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-5">
                <h4>Summary</h4>
                <div class="card p-3 mt-3">
                    <div class="mb-2">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'completed' ? 'success' : 'secondary') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <strong>Payment:</strong> {{ ucfirst($order->payment_status) }} ({{ str_replace('_', ' ', $order->payment_method) }})
                    </div>
                    <div class="mb-2">
                        <strong>Placed on:</strong> {{ optional($order->created_at)->format('Y-m-d H:i') }}
                    </div>
                    <div class="mb-2">
                        <strong>Shipping address:</strong>
                        <div>{{ $order->shipping_address }}</div>
                    </div>
                    <div class="mb-2">
                        <strong>Phone:</strong> {{ $order->phone }}
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <strong>${{ number_format($order->subtotal, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Tax</span>
                        <strong>${{ number_format($order->tax, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Discount</span>
                        <strong>-${{ number_format($order->discount, 2) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Total</span>
                        <strong>${{ number_format($order->total, 2) }}</strong>
                    </div>
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


