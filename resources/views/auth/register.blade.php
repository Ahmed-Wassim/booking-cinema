<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CineFlow</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('home/css/landing.css') }}">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
            background: linear-gradient(135deg, var(--bg-color), rgba(15, 23, 42, 0.9));
        }

        .auth-card {
            width: 100%;
            max-width: 500px;
            padding: 40px;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-logo {
            font-size: 2rem;
            margin-bottom: 8px;
            display: block;
        }

        .auth-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--surface-border);
            border-radius: var(--radius-md);
            color: white;
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.2);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .domain-input-group {
            display: flex;
            align-items: center;
        }

        .domain-input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .domain-suffix {
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--surface-border);
            border-left: none;
            border-top-right-radius: var(--radius-md);
            border-bottom-right-radius: var(--radius-md);
            color: var(--text-muted);
            font-size: 1rem;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
        }

        select.form-control option {
            background: var(--bg-color);
            color: white;
        }

        .alert {
            padding: 16px;
            border-radius: var(--radius-md);
            margin-bottom: 24px;
            font-size: 0.9rem;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #FCA5A5;
        }

        .alert-error ul {
            margin-top: 8px;
            margin-left: 20px;
            list-style-type: disc;
        }

        .auth-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--accent-primary);
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="auth-container">
        <div class="glass-card auth-card reveal visible">
            <div class="auth-header">
                <a href="{{ route('landing') }}" class="logo auth-logo">
                    <span class="logo-icon">🍿</span>
                    Cine<em>Flow</em>
                </a>
                <p class="auth-subtitle">Register your cinema and start selling tickets.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Whoops! Something went wrong.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('landlord.register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="company_name" class="form-label">Cinema Name</label>
                    <input type="text" id="company_name" name="company_name" class="form-control"
                        value="{{ old('company_name') }}" required autofocus placeholder="e.g. Starlight Drive-In">
                </div>

                <div class="form-group">
                    <label for="domain" class="form-label">Booking Subdomain</label>
                    <div class="domain-input-group">
                        <input type="text" id="domain" name="domain" class="form-control" value="{{ old('domain') }}"
                            required placeholder="starlight">
                        <span
                            class="domain-suffix">.{{ config('tenancy.central_domains')[0] ?? env('LANDLORD_DOMAIN', 'cinema.test') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Your Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required
                        placeholder="John Doe">
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required
                        placeholder="you@example.com">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required
                        placeholder="••••••••">
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                        required placeholder="••••••••">
                </div>



                <button type="submit" class="btn btn-primary btn-block btn-large" style="margin-top: 8px;">Submit
                    Registration Request</button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="{{ route('landlord.login') }}">Log in</a>
            </div>
        </div>
    </div>

</body>

</html>