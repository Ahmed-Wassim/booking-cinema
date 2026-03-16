<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - CineFlow</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('home/css/landing.css') }}">
    <style>
        .result-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--bg-color), rgba(15, 23, 42, 0.9));
            padding: 20px;
        }
        .result-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: var(--radius-lg);
            padding: 50px 40px;
            text-align: center;
            max-width: 500px;
            width: 100%;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 40px -10px rgba(16, 185, 129, 0.2);
        }
        .icon {
            width: 80px;
            height: 80px;
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 30px;
        }
        h1 {
            color: white;
            margin-bottom: 20px;
        }
        p {
            color: var(--text-muted);
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            color: white;
            padding: 14px 30px;
            border-radius: var(--radius-md);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(139, 92, 246, 0.5);
        }
    </style>
</head>
<body>
    <div class="result-container">
        <div class="result-card reveal visible">
            <div class="icon">✓</div>
            <h1>Payment Successful!</h1>
            <p>Thank you for subscribing. Your cinema registration has been completed and your plan is now active.</p>
            <p>We're setting up your workspace. You will receive an email shortly with your login details.</p>
            <a href="{{ route('landing') }}" class="btn">Return to Homepage</a>
        </div>
    </div>
</body>
</html>
