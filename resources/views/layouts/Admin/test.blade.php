

<!-- Profile Menu Item -->
<li class="menu-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
    <a href="{{ route('admin.profile.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user-circle"></i>
        <div data-i18n="Profile">My Profile</div>
    </a>
</li>

<!-- Settings Menu Item -->
<li class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
    <a href="{{ route('admin.settings.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-cog"></i>
        <div data-i18n="Settings">Settings</div>
    </a>
</li>

<li class="menu-header small text-uppercase"><span class="menu-header-text">User Management</span></li>


<li class="menu-item {{ request()->routeIs('admin.feeling.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-smile"></i>
        <div data-i18n="Feeling">Feeling Manage</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.feeling.index') ? 'active' : '' }}">
            <a href="{{ route('admin.feeling.index') }}" class="menu-link">
                <div data-i18n="Feeling Manage">Feeling Manage</div>
            </a>
        </li>
    </ul>
</li>

<li class="menu-item {{ request()->routeIs('admin.activity.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-run"></i>
        <div data-i18n="Activity">Activity Manage</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.activity.index') ? 'active' : '' }}">
            <a href="{{ route('admin.activity.index') }}" class="menu-link">
                <div data-i18n="Activity Manage">Activity Manage</div>
            </a>
        </li>
    </ul>
</li>