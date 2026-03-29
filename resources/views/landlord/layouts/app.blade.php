@php
    $locale = app()->getLocale();
    $isRtl  = in_array($locale, ['ar']);
    $dir    = $isRtl ? 'rtl' : 'ltr';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ $dir }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Dashboard'))</title>

    {{-- Bootstrap CSS (swap to RTL build when Arabic is active) --}}
    @if ($isRtl)
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Arabic: use a proper Arabic UI font --}}
    @if ($isRtl)
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif

    <link href="{{ asset('landlord/css/dashboard.css') }}?v={{ filemtime(public_path('landlord/css/dashboard.css')) }}" rel="stylesheet">
    @stack('styles')
</head>

<body class="{{ $isRtl ? 'rtl' : '' }}">

    {{-- SIDEBAR --}}
    <div class="sidebar" id="sidebar">
        @include('landlord.layouts.partials.sidebar')
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- MAIN WRAPPER --}}
    <div class="main-wrapper" id="mainWrapper">

        <header class="topbar d-flex justify-content-between align-items-center px-3">
            @include('landlord.layouts.partials.header')
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