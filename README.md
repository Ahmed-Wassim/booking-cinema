## Cinema SaaS – Landlord Panel

This repository contains a **multi-tenant cinema SaaS landlord application** built on **Laravel 12 (PHP 8.2+)**.  
The landlord app manages tenant registrations (cinemas), subscription plans, and payments, and integrates with external file storage and a payment gateway.

### Features (Current State)

- **Landlord domain routing**
  - All landlord-facing routes are scoped by `LANDLORD_DOMAIN` (see `routes/landlord.php` and `routes/web.php`).
  - Public landing page at `/` and registration flow at `/register`.
- **Authentication & verification**
  - Login, password reset, email verification, and logout flows for landlord users.
- **Admin dashboard**
  - `dashboard` view under the landlord domain.
  - Resource controllers for:
    - `users` (landlord users)
    - `tenants` (cinema tenants)
    - `plans` (subscription plans)
    - `payments` (admin view of payment records, index + show)
    - `suppliers` listing
  - Management of tenant registration requests (approve / reject).
- **Subscription payments**
  - Uses `paytabscom/laravel_paytabs` via a shared `PaymentManager` and `SubscriptionPaymentService`.
  - Payment routes (under landlord domain):
    - `GET /payment/plans` – list available subscription plans.
    - `POST /payment/pay/{plan}` – initiate payment for a plan.
    - `GET /payment/success` & `GET /payment/failure` – return URLs after payment.
    - `POST /payment/callback` – gateway callback endpoint.
  - `SubscriptionPaymentService`:
    - Validates payment request data and resolves the selected `Plan`.
    - Optionally links payment to a `RegistrationRequest`.
    - Creates a **pending** payment record (amount, currency, token, status).
    - Prepares safe return/callback URLs and delegates to the `PaymentManager`.
    - Handles gateway callbacks, verifies success/failure, and updates payment status (`PENDING` → `PAID` / `FAILED`).
- **File storage**
  - `FileStorageService` abstracts file handling on a configurable disk (default `public`):
    - Download and store files from a URL (e.g. movie posters/backdrops).
    - Store uploaded files with safe, slugged filenames.
    - Store raw string contents.
  - Uses Laravel’s `Storage` facade with logging and graceful failure handling.
- **Multi-tenancy**
  - Uses `stancl/tenancy` to support tenant separation (landlord-centric logic currently in the `App\Domain\Landlord` namespace).

### Tech Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Auth**: Laravel authentication stack (with sanctum available)
- **Multi-tenancy**: `stancl/tenancy`
- **Payments**: `paytabscom/laravel_paytabs` via shared payment manager
- **Front-end tooling**: Vite / NPM (standard Laravel setup)

### Getting Started (Docker)

#### Prerequisites

- Docker
- Docker Compose

#### 1. Clone and base configuration

```bash
git clone <this-repo-url> cinema
cd cinema

cp .env.example .env   # or copy manually
```

Update `.env` with at least:

- **Landlord domain**
  - `LANDLORD_DOMAIN=your-landlord-domain.test` (or the domain you map to the nginx container)
- **Database** (match the postgres service in `docker-compose.yml`)
  - `DB_CONNECTION=pgsql`
  - `DB_HOST=postgres`
  - `DB_PORT=5432`
  - `DB_DATABASE=cinema`
  - `DB_USERNAME=root`
  - `DB_PASSWORD=root`
- **Cache / queue**
  - `REDIS_HOST=redis`
- **PayTabs / payment gateway**
  - Credentials and configuration expected by `paytabscom/laravel_paytabs` and any keys used in `config/paytabs.php`.
- **Filesystem**
  - `FILESYSTEM_DISK=public` (or another disk used by `FileStorageService`).

#### 2. Build and start containers

From the project root:

```bash
docker compose up --build -d
```

The `app` container is built from the provided `Dockerfile` (PHP 8.3 FPM with Composer, PostgreSQL and Redis extensions, Supervisor, and custom PHP-FPM config).  
The `nginx` container serves the application on port `80` and shares the project + `vendor` volume with `app`.

After the containers are up the first time, exec into the `app` container to install PHP dependencies, run migrations, and build assets (if not already handled by your `start.sh` script):

```bash
docker compose exec app bash

composer install
php artisan key:generate
php artisan migrate --force

npm install
npm run build   # or `npm run dev` for hot-reload in development
```

#### 3. Accessing the application

- **HTTP**: `http://localhost` (or the host you bound to port 80) hitting the `nginx` container.
- **Landlord landing page**: `http://your-landlord-domain.test` (point this domain to `127.0.0.1` and ensure nginx/server config matches `LANDLORD_DOMAIN`).

You can manage containers with:

```bash
docker compose ps
docker compose logs -f
docker compose down
```

### Project Structure (Relevant Domains)

- `app/Domain/Landlord`
  - `Billing/Payment/Services/SubscriptionPaymentService.php` – subscription payment orchestration.
  - `Repositories` – interfaces and classes for landlord-side data access.
  - `Services` – landlord-specific domain services.
- `app/Domain/Shared`
  - `FileStorage/Services/FileStorageService.php` – cross-domain file storage service.
  - `Payments` – shared payment abstractions (e.g. `PaymentManager`, factories).
- `routes/landlord.php`
  - Landlord domain routes: auth, dashboard, tenants, plans, payments, suppliers, registration requests, and payment flow.
- `routes/web.php`
  - Landlord landing page (`/`) under `LANDLORD_DOMAIN`.

### Notes & Next Steps

- Tenant-side (cinema-facing) features can be added in parallel under their own domain and routes.
- Payment and storage services have been designed with clear interfaces to make it easy to swap implementations or add new gateways/disks later.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
