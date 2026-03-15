<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineFlow - The Ultimate Cinema Management Software</title>
    <meta name="description"
        content="Launch, manage, and scale your cinema business with our all-in-one multi-tenant ticketing and management platform.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('home/css/landing.css') }}">
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-content">
            <a href="#" class="logo">
                <span class="logo-icon">🍿</span>
                Cine<em>Flow</em>
            </a>
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#testimonials">Testimonials</a>
                <a href="#pricing">Pricing</a>
            </div>
            <div class="nav-actions">
                @auth('web')
                    <a href="{{ route('landlord.dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('landlord.login') }}" class="btn btn-outline">Log in</a>
                    <a href="{{ route('landlord.register') }}" class="btn btn-primary">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- 1. Hero Section -->
    <header class="hero" id="hero">
        <div class="hero-bg-glow"></div>
        <div class="container hero-content reveal">
            <div class="hero-badge">🎬 The #1 platform for independent cinemas</div>
            <h1 class="hero-title">Manage your cinema with <span>Effortless Precision</span></h1>
            <p class="hero-subtitle">The all-in-one multi-tenant platform for online ticketing, point of sale, seat
                mapping, and audience insights. Run your theater smoothly so you can focus on the show.</p>
            <div class="hero-cta">
                <a href="{{ route('landlord.register') }}" class="btn btn-primary btn-large">Start your free trial</a>
                <a href="#how-it-works" class="btn btn-secondary btn-large">See How It Works</a>
            </div>
            <div class="hero-image-wrapper">
                <div class="hero-image-glass">
                    <div class="mockup-header">
                        <span class="dot red"></span><span class="dot yellow"></span><span class="dot green"></span>
                    </div>
                    <div class="mockup-body">
                        <!-- Simulated dashboard look -->
                        <div class="skeleton skeleton-title" style="width: 30%;"></div>
                        <div class="skeleton-grid">
                            <div class="skeleton skeleton-card"
                                style="display:flex; align-items:center; justify-content:center;">Tickets Sold</div>
                            <div class="skeleton skeleton-card"
                                style="display:flex; align-items:center; justify-content:center;">Revenue</div>
                            <div class="skeleton skeleton-card"
                                style="display:flex; align-items:center; justify-content:center;">Active Screens</div>
                        </div>
                        <div class="skeleton skeleton-chart"
                            style="display:flex; align-items:center; justify-content:center;">Audience Analytics Chart
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- 2. Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header reveal">
                <h2 class="section-title">Everything you need to run a blockbuster operation</h2>
                <p class="section-subtitle">Powerful features tailored specifically for modern cinema management.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card glass-card reveal">
                    <div class="feature-icon bg-gradient-1">🎟️</div>
                    <h3>Online Ticketing</h3>
                    <p>Provide a seamless checkout experience for your patrons. Sell tickets directly from your
                        customized tenant domain.</p>
                </div>
                <div class="feature-card glass-card reveal">
                    <div class="feature-icon bg-gradient-2">💺</div>
                    <h3>Dynamic Seat Mapping</h3>
                    <p>Build visual seating charts for any auditorium layout. Let guests pick their perfect spot in
                        real-time.</p>
                </div>
                <div class="feature-card glass-card reveal">
                    <div class="feature-icon bg-gradient-3">💳</div>
                    <h3>Integrated POS</h3>
                    <p>Process in-person ticket and concessions sales lightning fast. Sync inventory seamlessly across
                        all terminals.</p>
                </div>
                <div class="feature-card glass-card reveal">
                    <div class="feature-icon bg-gradient-4">🎥</div>
                    <h3>Showtime Scheduling</h3>
                    <p>Easily drag-and-drop showtimes, manage film metadata, and distribute schedules automatically to
                        your website.</p>
                </div>
                <div class="feature-card glass-card reveal">
                    <div class="feature-icon bg-gradient-5">📈</div>
                    <h3>Advanced Reporting</h3>
                    <p>Track box office performance, attendance trends, and concession revenue with beautiful visual
                        dashboards.</p>
                </div>
                <div class="feature-card glass-card reveal">
                    <div class="feature-icon bg-gradient-6">🌐</div>
                    <h3>Multi-Tenant Architecture</h3>
                    <p>Your own sub-domain, your own database, and your own branding. Completely isolated and secure.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="section-header reveal">
                <h2 class="section-title">From zero to showtime</h2>
                <p class="section-subtitle">Get your theater up and running online in simply three steps.</p>
            </div>
            <div class="steps-container">
                <div class="step reveal">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Claim your domain</h3>
                        <p>Sign up and instantly get your unique tenant environment for your brand.</p>
                    </div>
                </div>
                <div class="step-connector reveal"></div>
                <div class="step reveal">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Setup your screens</h3>
                        <p>Use our visual builder to map out your auditoriums, seats, and concession menus.</p>
                    </div>
                </div>
                <div class="step-connector reveal"></div>
                <div class="step reveal">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Start selling tickets</h3>
                        <p>Publish your showtimes and watch the bookings roll in securely through our payment gateways.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Testimonials Section -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-header reveal">
                <h2 class="section-title">Adored by theater owners</h2>
                <p class="section-subtitle">Real feedback from actual cinemas using CineFlow daily.</p>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card glass-card reveal">
                    <div class="rating">★★★★★</div>
                    <p class="quote">"CineFlow completely transformed how we sell tickets. Having our own dedicated
                        portal with seat selection is a game-changer for our independent drive-in."</p>
                    <div class="author">
                        <div class="author-avatar pseudo-avatar-1"></div>
                        <div class="author-info">
                            <h4>Sarah Jenkins</h4>
                            <span>Owner, Starlight Drive-In</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card glass-card reveal">
                    <div class="rating">★★★★★</div>
                    <p class="quote">"Managing multiple screens used to be a nightmare of spreadsheets. Now, everything
                        from scheduling to concessions is centralized and beautiful."</p>
                    <div class="author">
                        <div class="author-avatar pseudo-avatar-2"></div>
                        <div class="author-info">
                            <h4>Marcus Chen</h4>
                            <span>Manager, Grand Metro Cinemas</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card glass-card reveal">
                    <div class="rating">★★★★★</div>
                    <p class="quote">"The onboarding was instant. Within ten minutes, I had my tenant sub-domain
                        configured, integrated Stripe, and began selling opening night tickets."</p>
                    <div class="author">
                        <div class="author-avatar pseudo-avatar-3"></div>
                        <div class="author-info">
                            <h4>Elena Rodriguez</h4>
                            <span>Director, The Indie Film House</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Pricing Section -->
    <section class="pricing" id="pricing">
        <div class="container">
            <div class="section-header reveal">
                <h2 class="section-title">Simple, transparent pricing</h2>
                <p class="section-subtitle">Choose the plan that fits the scale of your multiplex. No hidden ticketing
                    fees from us.</p>
            </div>
            <div class="pricing-grid">
                @if(isset($plans) && $plans->count() > 0)
                    @foreach($plans as $index => $plan)
                        @php
                            // Let's make the second plan "popular" just for UI presentation purposes
                            $isPopular = ($index == 1);
                        @endphp
                        <div class="pricing-card glass-card {{ $isPopular ? 'popular' : '' }} reveal">
                            @if($isPopular)
                                <div class="popular-badge">Most Popular</div>
                            @endif

                            <div class="plan-header">
                                <h3>{{ $plan->name }}</h3>
                                <p>{{ Str::limit($plan->description, 60, '...') }}</p>
                            </div>
                            <div class="plan-price">
                                <span class="currency">$</span>
                                <span class="amount">{{ number_format($plan->price) }}</span>
                                <span
                                    class="period">/{{ $plan->billing_interval === 'month' ? 'mo' : ($plan->billing_interval === 'year' ? 'yr' : $plan->billing_interval) }}</span>
                            </div>
                            <ul class="plan-features">
                                @if(isset($plan->features) && $plan->features->count() > 0)
                                    @foreach($plan->features as $feature)
                                        <li><span class="check">✓</span>
                                            @if($feature->feature_key ?? null)
                                                {{ Str::title(str_replace('_', ' ', $feature->feature_key->value)) }}: {{ $feature->feature_value }}
                                            @else
                                                {{ $feature->feature_value }}
                                            @endif
                                        </li>
                                    @endforeach
                                @else
                                    <li><span class="check">✓</span> Core ticketing features</li>
                                    <li><span class="check">✓</span> Dedicated tenant space</li>
                                @endif
                            </ul>
                            <a href="{{ route('landlord.register', ['plan_id' => $plan->id]) }}"
                                class="btn {{ $isPopular ? 'btn-primary' : 'btn-outline' }} btn-block">
                                {{ $plan->price > 0 ? 'Start Free Trial' : 'Get Started' }}
                            </a>
                        </div>
                    @endforeach
                @else
                    <!-- Fallback if database has no plans -->
                    <div class="pricing-card glass-card reveal" style="grid-column: 1 / -1; text-align: center;">
                        <h3>Stay Tuned</h3>
                        <p>Pricing plans are currently being updated.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- 6. Footer Section -->
    <footer class="footer" id="footer">
        <div class="container reveal">
            <div class="footer-cta glass-card">
                <h2>Ready for the premier showing?</h2>
                <p>Join hundreds of independent theater owners using CineFlow to pack their houses.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Enter your email" required>
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </form>
            </div>

            <div class="footer-bottom">
                <div class="footer-brand">
                    <a href="#" class="logo">
                        <span class="logo-icon">🍿</span>
                        Cine<em>Flow</em>
                    </a>
                    <p>&copy; {{ date('Y') }} CineFlow Systems. All rights reserved.</p>
                </div>
                <div class="footer-links">
                    <div class="link-group">
                        <h4>Platform</h4>
                        <a href="#features">Features</a>
                        <a href="#pricing">Pricing</a>
                        <a href="#">Hardware POS</a>
                        <a href="#">Showtime API</a>
                    </div>
                    <div class="link-group">
                        <h4>Company</h4>
                        <a href="#">About Us</a>
                        <a href="#">Careers</a>
                        <a href="#">Blog</a>
                        <a href="#">Contact</a>
                    </div>
                    <div class="link-group">
                        <h4>Legal</h4>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                        <a href="#">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('home/js/landing.js') }}"></script>
</body>

</html>