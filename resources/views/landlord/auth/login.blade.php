@extends('landlord.layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="auth-container">

        {{-- Left Panel --}}
        <div class="auth-panel auth-left d-none d-lg-flex">
            <div class="auth-branding">
                <div class="auth-logo">
                    <i class="bi bi-hexagon-fill"></i>
                    <span>{{ config('app.name', 'AdminKit') }}</span>
                </div>
                <div class="auth-illustration">
                    <div class="floating-card card-1">
                        <i class="bi bi-graph-up-arrow text-success"></i>
                        <div>
                            <div class="fw-semibold small">Revenue</div>
                            <div class="text-success small">+24.5%</div>
                        </div>
                    </div>
                    <div class="floating-card card-2">
                        <i class="bi bi-people-fill text-primary"></i>
                        <div>
                            <div class="fw-semibold small">Active Users</div>
                            <div class="text-muted small">12,847</div>
                        </div>
                    </div>
                    <div class="floating-card card-3">
                        <i class="bi bi-shield-check-fill text-success"></i>
                        <div class="small fw-semibold">Secure Dashboard</div>
                    </div>
                </div>
                <div class="auth-tagline">
                    <h2>Manage everything from one place</h2>
                    <p>A powerful, extensible admin dashboard built for modern teams.</p>
                </div>
            </div>
        </div>

        {{-- Right Panel --}}
        <div class="auth-panel auth-right">
            <div class="auth-form-wrapper">

                {{-- Mobile Logo --}}
                <div class="auth-logo d-flex d-lg-none mb-4">
                    <i class="bi bi-hexagon-fill"></i>
                    <span>{{ config('app.name', 'AdminKit') }}</span>
                </div>

                <h3 class="auth-title">Welcome back</h3>
                <p class="auth-subtitle">Sign in to your dashboard account</p>

                {{-- Session Status --}}
                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('landlord.login') }}" class="auth-form">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label for="password" class="form-label fw-medium">Password</label>
                            @if(Route::has('password.request'))
                                <a href="" class="small text-primary">Forgot password?</a>
                            @endif
                        </div>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required
                                autocomplete="current-password">
                            <button class="input-group-text border-start-0 bg-transparent toggle-password" type="button"
                                data-target="password">
                                <i class="bi bi-eye" id="passwordEye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-primary w-100 btn-lg auth-submit-btn">
                        <span class="btn-text">Sign In</span>
                        <i class="bi bi-arrow-right-circle ms-2"></i>
                    </button>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function () {
                const target = document.getElementById(this.dataset.target);
                const icon = this.querySelector('i');
                if (target.type === 'password') {
                    target.type = 'text';
                    icon.className = 'bi bi-eye-slash';
                } else {
                    target.type = 'password';
                    icon.className = 'bi bi-eye';
                }
            });
        });
    </script>
@endpush