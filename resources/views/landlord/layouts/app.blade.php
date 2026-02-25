<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Dashboard'))</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link href="{{ asset('landlord/css/dashboard.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>

    {{-- SIDEBAR --}}
    <div class="sidebar" id="sidebar">
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
            <div class="nav-section-label">Main</div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill nav-icon"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#usersMenu"
                        class="nav-link has-submenu {{ request()->routeIs('users.*') ? 'active' : '' }}"
                        data-bs-toggle="collapse">
                        <i class="bi bi-bar-chart-fill nav-icon"></i>
                        <span>Users</span>
                        <i class="bi bi-chevron-down nav-arrow"></i>
                    </a>

                    <div class="collapse {{ request()->routeIs('users.*') ? 'show' : '' }}" id="usersMenu">
                        <ul class="submenu">
                            <li><a href="#">List Users</a></li>
                            <li><a href="#">Create User</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <div class="user-mini d-flex align-items-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(optional(auth()->user())->name ?? 'User') }}&background=6366f1&color=fff&size=36"
                    class="user-avatar rounded-circle me-2">

                <div class="user-info">
                    <div class="user-name">{{ optional(auth()->user())->name ?? 'User Name' }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
        </div>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- MAIN WRAPPER --}}
    <div class="main-wrapper" id="mainWrapper">

        <header class="topbar d-flex justify-content-between align-items-center px-3">

            <div class="topbar-left d-flex align-items-center">
                <button class="topbar-btn sidebar-toggle me-2" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>

                <nav aria-label="breadcrumb" class="d-none d-md-flex">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Search..."
                            id="globalSearch">
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
                            <h6 class="mb-0 fw-semibold">Notifications</h6>
                            <a href="#" class="text-primary small">Mark all read</a>
                        </div>

                        <div class="notification-list">
                            @include('landlord.components.notification-item', ['icon' => 'bi-person-plus-fill', 'iconBg' => 'bg-primary', 'title' => 'New user registered', 'time' => '2 min ago'])
                            @include('landlord.components.notification-item', ['icon' => 'bi-cart-fill', 'iconBg' => 'bg-success', 'title' => 'New order #1042 placed', 'time' => '15 min ago'])
                            @include('landlord.components.notification-item', ['icon' => 'bi-exclamation-triangle-fill', 'iconBg' => 'bg-warning', 'title' => 'Server CPU usage high', 'time' => '1 hr ago'])
                        </div>

                        <div class="dropdown-footer p-2">
                            <a href="#" class="btn btn-sm btn-light w-100">View all notifications</a>
                        </div>
                    </div>
                </div>

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

                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <form method="POST" action="{{ route('landlord.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

            </div>
        </header>

        <main class="page-content">
            @foreach (['success', 'error', 'warning'] as $msg)
                @if(session($msg))
                    <div class="alert alert-{{ $msg === 'error' ? 'danger' : $msg }} alert-dismissible fade show">
                        <i class="bi bi-info-circle me-2"></i>{{ session($msg) }}
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            @endforeach

            @yield('content')
        </main>

        <footer class="page-footer d-flex justify-content-between px-3 py-2">
            <span>&copy; {{ date('Y') }} {{ config('app.name', 'AdminKit') }}</span>
            <span>Version 1.0.0</span>
        </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="{{ asset('landlord/js/dashboard.js') }}"></script>

    @stack('scripts')
</body>

</html>