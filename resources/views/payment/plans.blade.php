<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Plan - CineFlow</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('home/css/landing.css') }}">
    <style>
        .payment-container {
            min-height: 100vh;
            padding: 80px 24px;
            background: linear-gradient(135deg, var(--bg-color), rgba(15, 23, 42, 0.9));
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header-content {
            text-align: center;
            margin-bottom: 60px;
            max-width: 600px;
        }

        .header-content h1 {
            font-size: 2.5rem;
            margin-bottom: 16px;
            color: white;
        }

        .header-content p {
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            width: 100%;
            max-width: 1000px;
            justify-content: center;
        }

        .plan-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-lg);
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .plan-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-primary);
            box-shadow: 0 10px 30px -10px rgba(139, 92, 246, 0.5);
        }

        .plan-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            margin-bottom: 10px;
        }

        .plan-price {
            font-size: 3rem;
            font-weight: 700;
            color: var(--accent-primary);
            margin-bottom: 20px;
        }

        .plan-price span {
            font-size: 1rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .plan-features {
            list-style: none;
            padding: 0;
            margin: 0 0 30px 0;
            text-align: left;
            flex-grow: 1;
        }

        .plan-features li {
            padding: 10px 0;
            color: var(--text-primary);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .plan-features li::before {
            content: '✓';
            color: var(--accent-secondary);
            font-weight: bold;
        }

        .btn-subscribe {
            width: 100%;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-subscribe:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }

        .alert {
            padding: 16px;
            border-radius: var(--radius-md);
            margin-bottom: 30px;
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #FCA5A5;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6EE7B7;
        }
    </style>
</head>

<body>
    <div class="payment-container">
        <div class="header-content reveal visible">
            <h1>Choose Your Plan</h1>
            <p>Select the plan that fits your cinema's needs and complete your registration with a secure payment.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="plans-grid">
            @foreach($plans as $plan)
                <div class="plan-card reveal visible" style="transition-delay: {{ $loop->index * 100 }}ms">
                    <h3 class="plan-name">{{ $plan->name }}</h3>
                    <div class="plan-price">
                        {{ $currentCurrency->getSymbol() }}{{ number_format($plan->price) }}
                        <span>/ {{ $plan->billing_interval }}</span>
                    </div>

                    <ul class="plan-features">
                        @foreach($plan->features as $feature)
                            <li>{{ ucwords(str_replace('_', ' ', $feature->feature_key->value ?? $feature->feature_key)) }}:
                                {{ $feature->feature_value }}</li>
                        @endforeach
                    </ul>

                    <form action="{{ route('landlord.payment.pay', $plan) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-subscribe">Subscribe Now</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>