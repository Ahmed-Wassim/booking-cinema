<div class="topbar-left d-flex align-items-center">
    <button class="topbar-btn sidebar-toggle me-2" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <nav aria-label="breadcrumb" class="d-none d-md-flex">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#">{{ __('landlord.Home') }}</a></li>
            @yield('breadcrumb')
        </ol>
    </nav>
</div>

<div class="topbar-right d-flex align-items-center gap-2">

    {{-- Search --}}
    <div class="topbar-search d-none d-md-flex">
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-transparent border-end-0">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" class="form-control border-start-0 ps-0" placeholder="{{ __('landlord.Search...') }}" id="globalSearch">
        </div>
    </div>

    {{-- Notifications --}}
    <div class="dropdown">
        <button class="topbar-btn position-relative" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="notification-dot"></span>
        </button>

        <div class="dropdown-menu dropdown-menu-end notification-dropdown shadow-lg">
            <div class="dropdown-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">{{ __('landlord.Notifications') }}</h6>
                <a href="#" class="text-primary small">{{ __('landlord.Mark all read') }}</a>
            </div>

            <div class="notification-list">
                @include('landlord.components.notification-item', ['icon' => 'bi-person-plus-fill', 'iconBg' => 'bg-primary', 'title' => __('landlord.New user registered'), 'time' => __('landlord.2 min ago')])
                @include('landlord.components.notification-item', ['icon' => 'bi-cart-fill', 'iconBg' => 'bg-success', 'title' => __('landlord.New order #1042 placed'), 'time' => __('landlord.15 min ago')])
                @include('landlord.components.notification-item', ['icon' => 'bi-exclamation-triangle-fill', 'iconBg' => 'bg-warning', 'title' => __('landlord.Server CPU usage high'), 'time' => __('landlord.1 hr ago')])
            </div>

            <div class="dropdown-footer p-2">
                <a href="#" class="btn btn-sm btn-light w-100">{{ __('landlord.View all notifications') }}</a>
            </div>
        </div>
    </div>

    {{-- Language Switcher --}}
    @include('landlord.layouts.partials.language-switcher')

    {{-- Theme Toggle --}}
    <button class="topbar-btn" id="themeToggle">
        <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
    </button>

    {{-- User dropdown --}}
    <div class="dropdown">
        <button class="user-dropdown-btn d-flex align-items-center" data-bs-toggle="dropdown">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(optional(auth()->user())->name ?? 'User') }}&background=6366f1&color=fff&size=36"
                class="rounded-circle me-1">
            <span class="d-none d-md-inline">{{ optional(auth()->user())->name ?? 'User' }}</span>
            <i class="bi bi-chevron-down small ms-1"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow">
            <li class="px-3 py-2">
                <div class="fw-semibold">{{ optional(auth()->user())->name ?? 'User' }}</div>
                <div class="text-muted small">{{ optional(auth()->user())->email ?? 'user@example.com' }}
                </div>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>

            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>{{ __('landlord.Profile') }}</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>{{ __('landlord.Settings') }}</a></li>

            <li>
                <hr class="dropdown-divider">
            </li>

            <li>
                <form method="POST" action="{{ route('landlord.logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('landlord.Logout') }}
                    </button>
                </form>
            </li>
        </ul>
    </div>

</div>