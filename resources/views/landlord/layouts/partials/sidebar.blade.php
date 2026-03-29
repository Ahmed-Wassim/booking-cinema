<div class="sidebar-brand">
    <a href="#" class="brand-link">
        <div class="brand-icon">
            <i class="bi bi-hexagon-fill"></i>
        </div>
        <span class="brand-name">{{ config('app.name', 'AdminKit') }}</span>
    </a>
    <button class="sidebar-toggle-btn d-lg-none" id="sidebarClose">
        <i class="bi bi-x-lg"></i>
    </button>
</div>

<nav class="sidebar-nav">
    <div class="nav-section-label">{{ __('landlord.Main') }}</div>
    <ul class="nav-list">
        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill nav-icon"></i>
                <span>{{ __('landlord.Dashboard') }}</span>
            </a>
        </li>

        @can('viewAny', \App\Models\User::class)
        <li class="nav-item">
            <a href="#usersMenu"
                class="nav-link has-submenu {{ request()->routeIs('landlord.users.*') ? 'active' : '' }}"
                data-bs-toggle="collapse">
                <i class="bi bi-person-fill nav-icon"></i>
                <span>{{ __('landlord.Users') }}</span>
                <i class="bi bi-chevron-down nav-arrow"></i>
            </a>

            <div class="collapse {{ request()->routeIs('landlord.users.*') ? 'show' : '' }}" id="usersMenu">
                <ul class="submenu">
                    <li><a href="{{ route('landlord.users.index') }}"
                            class="{{ request()->routeIs('landlord.users.index') ? 'active' : '' }}">{{ __('landlord.List Users') }}</a></li>
                    @can('create', \App\Models\User::class)
                    <li><a href="{{ route('landlord.users.create') }}"
                            class="{{ request()->routeIs('landlord.users.create') ? 'active' : '' }}">{{ __('landlord.Create User') }}</a>
                    </li>
                    @endcan
                </ul>
            </div>
        </li>
        @endcan

        @can('viewAny', \App\Models\RegistrationRequest::class)
        <li class="nav-item">
            <a href="{{ route('landlord.registration-requests.index') }}" class="nav-link {{ request()->routeIs('landlord.registration-requests.*') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill nav-icon"></i>
                <span>{{ __('landlord.Registration Requests') }}</span>
            </a>
        </li>
        @endcan

        @can('viewAny', \App\Models\Tenant::class)
        <li class="nav-item">
            <a href="#tenantsMenu"
                class="nav-link has-submenu {{ request()->routeIs('landlord.tenants.*') ? 'active' : '' }}"
                data-bs-toggle="collapse">
                <i class="bi bi-building-fill nav-icon"></i>
                <span>{{ __('landlord.Tenants') }}</span>
                <i class="bi bi-chevron-down nav-arrow"></i>
            </a>

            <div class="collapse {{ request()->routeIs('landlord.tenants.*') ? 'show' : '' }}" id="tenantsMenu">
                <ul class="submenu">
                    <li><a href="{{ route('landlord.tenants.index') }}"
                            class="{{ request()->routeIs('landlord.tenants.index') ? 'active' : '' }}">{{ __('landlord.List Tenants') }}</a>
                    </li>
                    @can('create', \App\Models\Tenant::class)
                    <li><a href="{{ route('landlord.tenants.create') }}"
                            class="{{ request()->routeIs('landlord.tenants.create') ? 'active' : '' }}">{{ __('landlord.Create Tenant') }}</a></li>
                    @endcan
                </ul>
            </div>
        </li>
        @endcan


        @can('viewAny', \App\Models\Plan::class)
        <li class="nav-item">
            <a href="#plansMenu"
                class="nav-link has-submenu {{ request()->routeIs('landlord.plans.*') ? 'active' : '' }}"
                data-bs-toggle="collapse">
                <i class="bi bi-tags-fill nav-icon"></i>
                <span>{{ __('landlord.Plans') }}</span>
                <i class="bi bi-chevron-down nav-arrow"></i>
            </a>

            <div class="collapse {{ request()->routeIs('landlord.plans.*') ? 'show' : '' }}" id="plansMenu">
                <ul class="submenu">
                    <li><a href="{{ route('landlord.plans.index') }}"
                            class="{{ request()->routeIs('landlord.plans.index') ? 'active' : '' }}">{{ __('landlord.List Plans') }}</a>
                    </li>
                    @can('create', \App\Models\Plan::class)
                    <li><a href="{{ route('landlord.plans.create') }}"
                            class="{{ request()->routeIs('landlord.plans.create') ? 'active' : '' }}">{{ __('landlord.Create Plan') }}</a></li>
                    @endcan
                </ul>
            </div>
        </li>
        @endcan

        @can('viewAny', \App\Models\Payment::class)
        <li class="nav-item">
            <a href="{{ route('landlord.payments.index') }}" class="nav-link {{ request()->routeIs('landlord.payments.*') ? 'active' : '' }}">
                <i class="bi bi-wallet2 nav-icon"></i>
                <span>{{ __('landlord.Payments') }}</span>
            </a>
        </li>
        @endcan
    </ul>
</nav>

<div class="sidebar-footer">
    <div class="user-mini d-flex align-items-center">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(optional(auth()->user())->name ?? 'User') }}&background=6366f1&color=fff&size=36"
            class="user-avatar rounded-circle me-2">

        <div class="user-info">
            <div class="user-name">{{ optional(auth()->user())->name ?? 'User Name' }}</div>
            <div class="user-role">{{ __('landlord.Administrator') }}</div>
        </div>
    </div>
</div>