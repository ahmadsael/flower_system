<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Bloom & Blossom - Seasonal flowers, bouquets, and arrangements.">

	<title>Bloom & Blossom | Fresh Flowers</title>

	<link rel="shortcut icon" type="image/png" href="{{asset('web_assets/assets/img/favicon.png')}}">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="{{asset('web_assets/assets/css/all.min.css')}}">
	<link rel="stylesheet" href="{{asset('web_assets/assets/bootstrap/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('web_assets/assets/css/owl.carousel.css')}}">
	<link rel="stylesheet" href="{{asset('web_assets/assets/css/magnific-popup.css')}}">
	<link rel="stylesheet" href="{{asset('web_assets/assets/css/animate.css')}}">
	<link rel="stylesheet" href="{{asset('web_assets/assets/css/meanmenu.min.css')}}">
	<link rel="stylesheet" href="{{asset('web_assets/assets/css/main.css')}}">
	<link rel="stylesheet" href="{{asset('web_assets/assets/css/responsive.css')}}">
</head>
<body id="top">
	
	<div class="loader">
        <div class="loader-inner">
            <div class="circle"></div>
        </div>
    </div>
	
	<div class="top-header-area" id="sticker">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-sm-12 text-center">
					<div class="main-menu-wrap">
						<div class="site-logo">
							<a href="#top">
								<img src="{{asset('web_assets/assets/img/logo.png')}}" alt="Bloom & Blossom">
							</a>
						</div>

						<nav class="main-menu">
							<ul>
								<li class="current-list-item"><a href="#top">Home</a></li>
								<li><a href="#about">About</a></li>
								<li><a href="#products">Shop</a></li>
								<li><a href="#auth">Login / Sign Up</a></li>
							</ul>
						</nav>
						<div class="d-flex align-items-center justify-content-end">
							@auth('customer')
								<div class="header-icons me-3 text-white d-flex align-items-center">
									<span class="me-3">Hi, {{ auth('customer')->user()->name }}</span>
									<a class="me-3 text-white" href="{{ route('customer.orders.index') }}">My Orders</a>
									<a class="me-3 text-white" href="{{ route('customer.favorites.index') }}"><i class="fas fa-heart"></i></a>
									<a class="shopping-cart" href="{{ route('customer.cart.index') }}"><i class="fas fa-shopping-cart"></i></a>
								</div>
								<form action="{{ route('customer.logout') }}" method="POST" class="d-inline">
									@csrf
									<button type="submit" class="btn btn-link text-white p-0">Logout</button>
								</form>
							@else
								<a class="mobile-show search-bar-icon" href="#auth"><i class="fas fa-user"></i></a>
							@endauth
							<div class="mobile-menu"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="hero-area hero-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-9 offset-lg-2 text-center">
					<div class="hero-text">
						<div class="hero-text-tablecell">
							<p class="subtitle">Bloom & Blossom</p>
							<h1>Premium Seasonal Flowers</h1>
							<p class="mt-3 text-white">Hand-tied bouquets, fresh stems, and farmer-grown arrangements delivered with care.</p>
							<div class="hero-btns">
								<a href="#products" class="boxed-btn">View Collection</a>
								<a href="#auth" class="bordered-btn">Login / Sign Up</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="list-section pt-80 pb-80" id="about">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
					<div class="list-box d-flex align-items-center">
						<div class="list-icon">
							<i class="fas fa-seedling"></i>
						</div>
						<div class="content">
							<h3>Farmer Fresh</h3>
							<p>Locally grown blooms cut the same day.</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
					<div class="list-box d-flex align-items-center">
						<div class="list-icon">
							<i class="fas fa-shipping-fast"></i>
						</div>
						<div class="content">
							<h3>Same-Day Delivery</h3>
							<p>On bouquets ordered before noon.</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="list-box d-flex justify-content-start align-items-center">
						<div class="list-icon">
							<i class="fas fa-heart"></i>
						</div>
						<div class="content">
							<h3>Crafted With Love</h3>
							<p>Designed by artisans for every occasion.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="product-section mt-150 mb-150" id="products">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="section-title">	
						<h3><span class="orange-text">Our</span> Bouquets</h3>
						<p>Discover seasonal stems, lush arrangements, and gifts for every celebration.</p>
					</div>
				</div>
			</div>

			<div class="row">
				@if(isset($products) && $products->count())
					@foreach($products as $product)
						<div class="col-lg-4 col-md-6 text-center mb-4">
							<div class="single-product-item h-100 d-flex flex-column">
								<div class="product-image position-relative">
									@php
										$imagePath = $product->image ? asset('storage/' . $product->image) : asset('web_assets/assets/img/products/product-img-1.jpg');
									@endphp
									<a href="{{ route('product.show', $product->slug) }}">
										<img src="{{ $imagePath }}" alt="{{ $product->name }}" style="height:260px; object-fit:cover;">
									</a>
									@auth('customer')
										<form action="{{ route('customer.favorites.toggle', $product->id) }}" method="POST" class="position-absolute" style="top:10px; right:10px;">
											@csrf
											<button type="submit" class="btn btn-sm {{ auth('customer')->user()->favorites->contains($product->id) ? 'btn-danger' : 'btn-outline-light' }}">
												<i class="fas fa-heart"></i>
											</button>
										</form>
									@endauth
								</div>
								<h3 class="mt-3">{{ $product->name }}</h3>
								<p class="product-price">
									<span>Per bouquet</span> ${{ number_format($product->price, 2) }}
								</p>
								<p class="px-3 flex-grow-1">
									{{ \Illuminate\Support\Str::limit($product->description ?? 'Fresh floral arrangement crafted by our farmers.', 80) }}
								</p>
								@auth('customer')
									<form action="{{ route('customer.cart.add', $product->id) }}" method="POST" class="mt-auto">
										@csrf
										<input type="hidden" name="quantity" value="1">
										<button type="submit" class="cart-btn w-100">
											<i class="fas fa-shopping-basket"></i> Add to Cart
										</button>
									</form>
								@else
									<a href="{{ route('home', ['section' => 'login']) }}#auth" class="cart-btn mt-auto">
										<i class="fas fa-shopping-basket"></i> Order / Login
									</a>
								@endauth
							</div>
						</div>
					@endforeach
				@else
					<div class="col-12 text-center">
						<p class="lead">Fresh bouquets are being arranged. Please check back soon.</p>
					</div>
				@endif
			</div>
		</div>
	</div>

	<section class="cart-banner pt-100 pb-100">
    	<div class="container">
        	<div class="row clearfix align-items-center">
            	<div class="image-column col-lg-6">
                	<div class="image">
                    	<img src="{{asset('web_assets/assets/img/a.jpg')}}" alt="Flower arrangement">
                    </div>
                </div>
                <div class="content-column col-lg-6">
					<h3><span class="orange-text">Bloom of</span> the month</h3>
                    <h4>Sunrise Peony Bouquet</h4>
                    <div class="text">Layered peonies and garden roses in peach and blush tones. Designed by our farmers and delivered with a handwritten note.</div>
                	<a href="#products" class="cart-btn mt-3"><i class="fas fa-seedling"></i> Shop Bouquets</a>
                </div>
            </div>
        </div>
    </section>

	<div class="latest-news pt-150 pb-150" id="auth">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="section-title">	
						<h3><span class="orange-text">Customer</span> Access</h3>
						<p>Log in to manage your orders or create an account to start sending flowers.</p>
					</div>
				</div>
			</div>

			@if (session('success_message'))
				<div class="alert alert-success text-center">
					{{ session('success_message') }}
				</div>
			@endif
			@if (session('error_message'))
				<div class="alert alert-danger text-center">
					{{ session('error_message') }}
				</div>
			@endif
			@if ($errors->any())
				<div class="alert alert-danger">
					<ul class="mb-0">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<div class="row">
				<div class="col-lg-5 offset-lg-1 col-md-6">
					<div class="single-latest-news p-4 shadow-sm rounded" style="background:#fff;">
						<h3 class="mb-3">Customer Login</h3>
						<form action="{{ route('customer.login') }}" method="POST" class="needs-validation" novalidate>
							@csrf
							<div class="form-group mb-3">
								<label class="mb-1 fw-semibold">Email</label>
								<input type="email" name="email" class="form-control" placeholder="you@example.com" required value="{{ old('email') }}">
							</div>
							<div class="form-group mb-3">
								<label class="mb-1 fw-semibold">Password</label>
								<input type="password" name="password" class="form-control" placeholder="••••••••" required>
							</div>
							<div class="form-group form-check mb-3">
								<input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
								<label class="form-check-label" for="remember">Remember me</label>
							</div>
							<button type="submit" class="cart-btn w-100 text-center">Login</button>
						</form>
					</div>
				</div>
				<div class="col-lg-5 col-md-6 mt-4 mt-md-0">
					<div class="single-latest-news p-4 shadow-sm rounded" style="background:#fff;">
						<h3 class="mb-3">Create Account</h3>
						<form action="{{ route('customer.register') }}" method="POST" class="needs-validation" novalidate>
							@csrf
							<div class="form-group mb-3">
								<label class="mb-1 fw-semibold">Full Name</label>
								<input type="text" name="name" class="form-control" placeholder="Full name" value="{{ old('name') }}" required>
							</div>
							<div class="form-group mb-3">
								<label class="mb-1 fw-semibold">Email</label>
								<input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required>
							</div>
							<div class="form-group mb-3">
								<label class="mb-1 fw-semibold">Phone</label>
								<input type="text" name="phone" class="form-control" placeholder="Phone number" value="{{ old('phone') }}" required>
							</div>
							<div class="form-group mb-3">
								<label class="mb-1 fw-semibold">Gender</label>
								<select name="gender" class="form-control" required>
									<option value="male" @selected(old('gender') === 'male')>Male</option>
									<option value="female" @selected(old('gender') === 'female')>Female</option>
								</select>
							</div>
							<div class="form-group mb-3">
								<label class="mb-1 fw-semibold">Birthday (optional)</label>
								<input type="date" name="birthday" class="form-control" value="{{ old('birthday') }}">
							</div>
							<div class="form-group mb-3">
								<label class="mb-1 fw-semibold">Age (optional)</label>
								<input type="number" name="age" class="form-control" value="{{ old('age') }}" min="0" max="120">
							</div>
							<div class="form-group mb-3">
								<label class="mb-1 fw-semibold">Password</label>
								<input type="password" name="password" class="form-control" placeholder="Create a strong password" required>
							</div>
							<div class="form-group mb-3">
								<label class="mb-1 fw-semibold">Confirm Password</label>
								<input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter password" required>
							</div>
							<button type="submit" class="cart-btn w-100 text-center">Sign Up</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- footer -->
	<div class="footer-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<div class="footer-box about-widget">
						<h2 class="widget-title">About us</h2>
						<p>Ut enim ad minim veniam perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae.</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box get-in-touch">
						<h2 class="widget-title">Get in Touch</h2>
						<ul>
							<li>34/8, East Hukupara, Gifirtok, Sadan.</li>
							<li>support@fruitkha.com</li>
							<li>+00 111 222 3333</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box pages">
						<h2 class="widget-title">Pages</h2>
						<ul>
							<li><a href="index.html">Home</a></li>
							<li><a href="about.html">About</a></li>
							<li><a href="services.html">Shop</a></li>
							<li><a href="news.html">News</a></li>
							<li><a href="contact.html">Contact</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box subscribe">
						<h2 class="widget-title">Subscribe</h2>
						<p>Subscribe to our mailing list to get the latest updates.</p>
						<form action="index.html">
							<input type="email" placeholder="Email">
							<button type="submit"><i class="fas fa-paper-plane"></i></button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end footer -->
	
	<!-- copyright -->
	<div class="copyright">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-12">
					<p>Copyrights &copy; 2019 - <a href="https://imransdesign.com/">Imran Hossain</a>,  All Rights Reserved.</p>
				</div>
				<div class="col-lg-6 text-right col-md-12">
					<div class="social-icons">
						<ul>
							<li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-linkedin"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-dribbble"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end copyright -->
	
	<!-- jquery -->
	<script src="{{asset('web_assets/assets/js/jquery-1.11.3.min.js')}}"></script>
	<!-- bootstrap -->
	<script src="{{asset('web_assets/assets/bootstrap/js/bootstrap.min.js')}}"></script>
	<!-- count down -->
	<script src="{{asset('web_assets/assets/js/jquery.countdown.js')}}"></script>
	<!-- isotope -->
	<script src="{{asset('web_assets/assets/js/jquery.isotope-3.0.6.min.js')}}"></script>
	<!-- waypoints -->
	<script src="{{asset('web_assets/assets/js/waypoints.js')}}"></script>
	<!-- owl carousel -->
	<script src="{{asset('web_assets/assets/js/owl.carousel.min.js')}}"></script>
	<!-- magnific popup -->
	<script src="{{asset('web_assets/assets/js/jquery.magnific-popup.min.js')}}"></script>
	<!-- mean menu -->
	<script src="{{asset('web_assets/assets/js/jquery.meanmenu.min.js')}}"></script>
	<!-- sticker js -->
	<script src="{{asset('web_assets/assets/js/sticker.js')}}"></script>
	<!-- main js -->
	<script src="{{asset('web_assets/assets/js/main.js')}}"></script>
	@if(!empty($authSection))
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const target = document.getElementById('auth');
			if (target) {
				target.scrollIntoView({ behavior: 'smooth' });
			}
		});
	</script>
	@endif

</body>
</html>
