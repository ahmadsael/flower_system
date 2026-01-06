<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <style>
        /* Custom sidebar hover color */
        #layout-menu .menu-link:hover,
        #layout-menu .menu-link:focus,
        #layout-menu .menu-link.active,
        #layout-menu .menu-item.active > .menu-link {
            background-color: #E2863D !important;
            color: #fff !important;
        }
        #layout-menu .menu-link:hover .menu-icon,
        #layout-menu .menu-link:focus .menu-icon,
        #layout-menu .menu-link.active .menu-icon,
        #layout-menu .menu-item.active > .menu-link .menu-icon {
            color: #fff !important;
        }
    </style>
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                @php
                    // Fetch the sidebar logo from settings, fallback to default if not set
                    $sidebarLogo = \App\Models\Setting::where('key', 'sidebar_logo')->value('value');
                @endphp
                <img 
                    src="{{ $sidebarLogo ? asset($sidebarLogo) : asset('dashboard_assets/assets/img/sidebar/sloopify-logo.svg') }}" 
                    alt="Logo" 
                    width="150px" 
                    height="150px" 
                    style="padding-top: 45px ; padding-bottom: 45px ; padding-left: 40px"
                >
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('farmer.dashboard') ? 'active' : '' }}">
            <a href="{{ route('farmer.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>


           
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Farmer Management</span></li>    

<li class="menu-item {{ request()->routeIs('farmer.product.*') && !request()->routeIs('farmer.product.role.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-spa"></i>
        <div data-i18n="Product">Product Manage</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('farmer.product.index') ? 'active' : '' }}">
            <a href="{{ route('farmer.product.index') }}" class="menu-link">
                <div data-i18n="Product Manage">Product Manage</div>
            </a>
        </li>
    </ul>
</li>

<li class="menu-item {{ request()->routeIs('farmer.order.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-cart"></i>
        <div data-i18n="Order">Order Manage</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('farmer.order.index') ? 'active' : '' }}">
            <a href="{{ route('farmer.order.index') }}" class="menu-link">
                <div data-i18n="Order Manage">Order Manage</div>
            </a>
        </li>
    </ul>
</li>

<li class="menu-item {{ request()->routeIs('farmer.wallet.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-wallet"></i>
        <div data-i18n="Wallet">Wallet Manage</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('farmer.wallet.index') ? 'active' : '' }}">
            <a href="{{ route('farmer.wallet.index') }}" class="menu-link">
                <div data-i18n="Wallet Manage">Wallet Manage</div>
            </a>
        </li>
    </ul>
</li>


<!-- <li class="menu-header small text-uppercase"><span class="menu-header-text">Actor Management</span></li>   -->
     


    </ul>
</aside>
