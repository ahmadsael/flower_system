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
        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>


           
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Admin Management</span></li>    

<li class="menu-item {{ request()->routeIs('admin.admin.*') && !request()->routeIs('admin.admin.role.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div data-i18n="Admin">Admin Manage</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.admin.index') ? 'active' : '' }}">
            <a href="{{ route('admin.admin.index') }}" class="menu-link">
                <div data-i18n="Admin Manage">Admin Manage</div>
            </a>
        </li>
    </ul>
</li>

<li class="menu-item {{ request()->routeIs('admin.admin.role.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
        <div data-i18n="Role">Role Manage</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.admin.role.index') ? 'active' : '' }}">
            <a href="{{ route('admin.admin.role.index') }}" class="menu-link">
                <div data-i18n="Role Manage">Role Manage</div>
            </a>
        </li>
    </ul>
</li>


<li class="menu-header small text-uppercase"><span class="menu-header-text">Actor Management</span></li>  
     

<li class="menu-item {{ request()->routeIs('admin.farmer.*') && !request()->routeIs('admin.farmer.withdrawal.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-user"></i>

        <div data-i18n="Farmer">Farmer Manage</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.farmer.index') ? 'active' : '' }}">
            <a href="{{ route('admin.farmer.index') }}" class="menu-link">
                <div data-i18n="Farmer Manage">Farmer Manage</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.farmer.withdrawal.*') ? 'active' : '' }}">
            <a href="{{ route('admin.farmer.withdrawal.index') }}" class="menu-link">
                <div data-i18n="Withdrawal Requests">Withdrawal Requests</div>
            </a>
        </li>
    </ul>
</li>

<li class="menu-item {{ request()->routeIs('admin.customer.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-smile"></i>
        <div data-i18n="Feeling">Customer Manage</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.customer.index') ? 'active' : '' }}">
            <a href="{{ route('admin.customer.index') }}" class="menu-link">
                <div data-i18n="Customer Manage">Customer Manage</div>
            </a>
        </li>
    </ul>
</li>

<li class="menu-item {{ request()->routeIs('admin.category.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-category"></i>
        <div data-i18n="Feeling">Category Manage</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.category.index') ? 'active' : '' }}">
            <a href="{{ route('admin.category.index') }}" class="menu-link">
                <div data-i18n="Category Manage">Category Manage</div>
            </a>
        </li>
    </ul>
</li>

    </ul>
</aside>
