# Cinema

Cinema is a multi-tenant booking SaaS platform for cinemas and event booking, built with Laravel 12 and PHP 8.2+.

It combines three application surfaces in one codebase:

- A landlord web application for operating the SaaS itself.
- A tenant dashboard API for each cinema tenant.
- A tenant public booking API for customer-facing movie discovery, seat reservation, checkout, and ticket delivery.

The codebase is not a plain Laravel CRUD app. It uses a domain-oriented structure with service, repository, DTO, and pipeline layers on top of Laravel's routing, Eloquent, events, queues, policies, and middleware.

## Related Repositories

This backend is part of a larger multi-repo system:

- Next.js frontend: https://github.com/Ahmed-Wassim/next-cinema
- Go sync service: https://github.com/Ahmed-Wassim/go-cinema-sync

Notes:

- The Next.js project is the frontend companion for the tenant-facing experience and is vibe coded.
- The Go service is the external synchronization service used by this Laravel app when showtime changes are pushed out through `SyncShowtimeToGo`.

## What The Project Does

At a high level, the platform supports the full lifecycle of a cinema SaaS product:

- Register and manage cinema tenants.
- Manage subscription plans and landlord-side payments.
- Provision isolated tenant databases automatically.
- Sync movies and genres from external suppliers such as TMDB.
- Let each tenant manage branches, halls, seats, movies, showtimes, discounts, staff users, and payments.
- Expose customer-facing tenant APIs for browsing movies, selecting seats, applying coupons, creating bookings, and initiating checkout.
- Generate tickets, send ticket emails, and log tenant activity.
- Sync showtime changes to an external Go service.

## Main Product Areas

### 1. Landlord application

The landlord side runs on the central domain defined by `LANDLORD_DOMAIN`.

It includes:

- Landing page and registration flow.
- Landlord authentication with email verification and password reset.
- Dashboard screens for users, tenants, plans, payments, suppliers, and registration requests.
- Subscription payment flow for onboarding or plan purchase.
- Movie and genre synchronization from upstream suppliers.

Important routes:

- `routes/web.php`: central landing page.
- `routes/landlord.php`: landlord auth, dashboard, resources, and subscription payment flow.

### 2. Tenant dashboard API

The tenant admin surface is an API mounted per tenant domain and protected by tenant JWT authentication.

It includes:

- Tenant auth endpoints.
- CRUD APIs for users, branches, halls, seats, price tiers, movies, showtimes, discounts, and payments.
- Showtime offer management.
- Ticket validation.
- Activity log retrieval.

Important route file:

- `routes/tenant.php`

### 3. Tenant public booking API

This is the public tenant-facing API used by the cinema storefront or frontend app.

It includes:

- Home and movie details endpoints.
- Seat map retrieval.
- Seat reservation.
- Booking creation.
- Payment initiation and callback handling.
- Coupon validation.
- Booking confirmation retrieval.

Important route file:

- `routes/tenant_home.php`

In the broader system, this API is intended to be consumed by the companion Next.js project:

- https://github.com/Ahmed-Wassim/next-cinema

## Tech Stack

### Backend

- PHP 8.2+ required by Composer.
- Laravel 12 as the application framework.
- Eloquent ORM for persistence.
- Laravel queues, scheduler, events, mail, middleware, validation, and policies.
- Pest for tests.

### Multi-tenancy

- `stancl/tenancy`
- Domain-based tenant identification.
- Separate central and tenant databases.
- Tenant-aware bootstrapping for database, cache, filesystem, queue, and permission cache.

### Authentication and authorization

- Central landlord auth via Laravel session guard (`web`).
- Tenant dashboard auth via JWT guard (`tenant`) using `tymon/jwt-auth`.
- `spatie/laravel-permission` for roles and permissions.
- Laravel policies attached to models with `#[UsePolicy(...)]`.
- `laravel/sanctum` is installed but the tenant admin API currently authenticates through JWT, not Sanctum.

### Payments

- `paytabscom/laravel_paytabs`
- Shared payment abstractions in `app/Domain/Shared/Payments`
- Landlord subscription payments and tenant booking payments both flow through a shared `PaymentManager`

### External integrations

- TMDB movie supplier integration.
- IMDb supplier abstraction exists in the shared supplier factory.
- External Go microservice sync on showtime changes.
- Mail delivery through Laravel mailers; Docker setup includes MailHog.
- Companion Next.js frontend project for the client-facing app.

### Documents and media

- `dompdf/dompdf`
- `spatie/laravel-pdf`
- `simplesoftwareio/simple-qrcode`
- Shared file storage service abstraction for downloaded and uploaded assets.

### Internationalization and content

- `mcamara/laravel-localization`
- Translations present for English, Arabic, and French.
- `spatie/laravel-translatable` for JSON-based translatable tenant fields.

### Infrastructure

- PostgreSQL 16 in Docker for the central database.
- Redis for cache and/or queue support.
- Nginx + PHP-FPM + Supervisor in Docker.
- Database queue driver is the current default in `config/queue.php`.

## Architecture Overview

### Runtime boundaries

The project is split into central and tenant runtime contexts:

- Central context:
  - Landlord users
  - Tenant registration and provisioning
  - Plans, subscriptions, suppliers, central movie catalog, and landlord payments
- Tenant context:
  - Tenant users
  - Branches, halls, seats, price tiers
  - Tenant movies, showtimes, discounts
  - Customers, bookings, booking seats, tenant payments, tickets, activity logs

Tenancy is initialized by domain using Stancl Tenancy middleware. Tenant routes are protected from central domains through `PreventAccessFromCentralDomains`.

### Database model

The application uses two migration sets:

- `database/migrations`
  - Central database schema
  - Tenants, domains, plans, subscriptions, landlord payments, suppliers, movies, genres, central users
- `database/migrations/tenant`
  - Tenant database schema
  - Users, branches, halls, seats, price tiers, movies, showtimes, showtime seats, customers, bookings, booking seats, payments, tickets, discounts, currencies, permissions, activity logs

Tenant databases are created and migrated automatically when a tenant is created. This is handled in `app/Providers/TenancyServiceProvider.php` through a Stancl JobPipeline using:

- `CreateDatabase`
- `MigrateDatabase`

### Layered domain structure

The project introduces a domain layer under `app/Domain`:

- `app/Domain/Landlord`
- `app/Domain/Tenant`
- `app/Domain/Shared`

Each module commonly contains:

- `DTO`
- `Repositories/Interfaces`
- `Repositories/Classes`
- `Services/Interfaces`
- `Services/Classes`
- Provider injectors to bind interfaces to concrete classes

This gives the project a service-oriented, domain-focused structure on top of Laravel's standard MVC.

### Request flow style

The common application flow is:

1. Route receives the request.
2. Controller validates or delegates validation to a Form Request.
3. Data is transformed into a DTO in many write flows.
4. Controller calls a domain service via an interface.
5. Service uses repositories for persistence and orchestration.
6. Resource classes transform API output where applicable.
7. Events, queued listeners, and jobs handle side effects.

That pattern is visible in landlord web modules, tenant dashboard APIs, and the tenant booking flow.

## Key Domains And Modules

### Landlord domain

Main namespace: `app/Domain/Landlord`

Key modules:

- `Dashboard/Web/User`
- `Dashboard/Web/Tenant`
- `Dashboard/Web/Plan`
- `Dashboard/Web/RegistrationRequest`
- `Dashboard/Web/Payment`
- `Dashboard/Web/Subscription`
- `Dashboard/Web/Supplier`
- `Dashboard/Web/SupplierSetting`
- `Dashboard/Web/Movie`
- `Dashboard/Web/Genre`
- `Billing/Payment`
- `MovieSync`

Responsibilities:

- Landlord admin CRUD and back office operations.
- Subscription plan management.
- Payment tracking for landlord flows.
- Tenant creation and removal.
- Supplier configuration and catalog sync.

### Tenant dashboard domain

Main namespace: `app/Domain/Tenant/Dashboard/Api`

Key modules:

- `User`
- `Branch`
- `Hall`
- `Seat`
- `PriceTier`
- `Movie`
- `Showtime`
- `ShowtimeSeat`
- `Discount`
- `Payment`
- `ActivityLog`

Responsibilities:

- Admin APIs for cinema operators and staff.
- Operational CRUD over cinema inventory and scheduling.
- Ticket validation and reporting support.

### Tenant public/home domain

Main namespace: `app/Domain/Tenant/Home`

Key modules:

- `Movie`
- `Showtime`
- `Seat`
- `Coupon`
- `Booking`

Responsibilities:

- Public movie browsing and details.
- Showtime discovery.
- Seat selection and reservation logic.
- Discount validation.
- Booking creation and confirmation.

### Shared domain

Main namespace: `app/Domain/Shared`

Key modules:

- `Payments`
- `Suppliers`
- `FileStorage`
- `Currency`
- `ExchangeRate`
- `Repositories`
- `DTO`

Responsibilities:

- Reusable payment orchestration.
- External supplier abstraction.
- Shared repository base class.
- Currency selection and exchange-rate related services.
- File storage helper services.

## Design Patterns Used

This project uses several concrete design patterns, not just generic Laravel conventions.

### 1. Repository pattern

Repositories wrap model access behind interfaces and concrete classes.

Examples:

- `app/Domain/Shared/Repositories/Classes/AbstractRepository.php`
- landlord repositories under `app/Domain/Landlord/.../Repositories`
- tenant repositories under `app/Domain/Tenant/.../Repositories`

Why it is used here:

- Centralizes query logic.
- Keeps controllers and services thinner.
- Makes service-layer testing easier.
- Supports interface-based dependency injection.

### 2. Service layer pattern

Business logic is moved from controllers into dedicated services.

Examples:

- `SubscriptionPaymentService`
- `OrderPaymentService`
- `BookingService`
- `MovieSyncService`
- tenant dashboard services such as `ShowtimeService`, `BranchService`, `SeatService`

Why it is used here:

- Separates orchestration from HTTP concerns.
- Makes workflows reusable across controllers or jobs.
- Improves testability and maintainability.

### 3. DTO pattern

Several write flows convert validated request data into DTO objects before handing data to services.

Examples:

- `TenantDTO`
- `UserDTO`
- `PlanDTO`
- `ShowtimeDTO`
- `BookingDTO`
- shared supplier DTOs such as `MovieDTO` and `MovieDetailsDTO`

Why it is used here:

- Defines stable input contracts between controller and service layers.
- Reduces direct coupling to raw request arrays.

### 4. Factory pattern

Factories choose concrete implementations based on runtime input.

Examples:

- `PaymentGatewayFactory`
- `MovieSupplierFactory`

Why it is used here:

- Encapsulates provider selection.
- Makes it easier to add new payment gateways or movie suppliers later.

### 5. Strategy pattern

The factory-selected gateway or supplier implementations behave like strategies behind common contracts.

Examples:

- `PaymentGateway` implementations such as `PaytabsGateway`
- `MovieSupplier` implementations such as `TMDBMovieSupplier` and `IMDbMovieSupplier`

Why it is used here:

- Lets the application swap external integrations without changing calling code.

### 6. Pipeline pattern

The booking flow is assembled as a sequence of pipe classes using Laravel's `Pipeline`.

Examples in `app/Domain/Tenant/Home/Booking/Pipes`:

- `CheckSeatAvailability`
- `ReserveSeats`
- `ApplyShowtimeOffer`
- `ApplyDiscount`
- `ResolveCustomer`
- `CreateBookingRecord`
- `GenerateTickets`
- `SendTicketEmail`

Why it is used here:

- Keeps complex booking logic composable.
- Makes each booking step small and focused.
- Supports transactional orchestration in `BookingService`.

### 7. Event-driven pattern

The application emits domain events and reacts through listeners and queued handlers.

Examples:

- `BookingConfirmed` -> ticket generation and email delivery flow
- `ShowtimeChanged` -> queued sync to the Go service
- tenancy lifecycle events in `TenancyServiceProvider`

Why it is used here:

- Separates side effects from primary write flows.
- Makes integrations and async work easier to extend.

### 8. Dependency injection and interface binding

Interfaces are bound to concrete implementations in provider injectors.

Examples:

- `app/Domain/Landlord/Dashboard/Web/Providers/Injectors`
- `app/Domain/Tenant/Dashboard/Api/Providers/Injectors`
- `app/Domain/Tenant/Home/Providers/Injectors`

Why it is used here:

- Keeps controllers and services decoupled from implementations.
- Improves modularity and testability.

### 9. Policy-based authorization

Authorization logic is organized with Laravel policies and model policy attributes.

Examples:

- `app/Policies`
- `app/Policies/Tenant`

Why it is used here:

- Keeps access control centralized.
- Fits both landlord and tenant models cleanly.

## Important Business Flows

### Tenant provisioning

When a tenant is created:

1. A central tenant record is created.
2. Stancl tenancy triggers tenant lifecycle events.
3. A tenant database is created.
4. Tenant migrations are executed.
5. Tenant routes become available on the tenant domain.

### Movie synchronization

Movie sync is handled by `SyncMoviesJob` and `MovieSyncService`.

Flow:

1. Scheduled job runs every six hours.
2. Supplier is resolved by key.
3. Supplier settings and API key are loaded.
4. Genres are synchronized.
5. TMDB endpoints are fetched, cached, merged, deduplicated, and stored.
6. Movies are linked to genres in the central catalog.

### Booking and checkout

The customer booking flow spans public tenant APIs, pipelines, payments, and events.

Flow:

1. Customer queries movies and showtimes.
2. Seat reservation endpoint reserves seats for a showtime.
3. Booking creation runs the booking pipeline inside a database transaction.
4. Checkout initiation creates a pending tenant payment and redirects to PayTabs.
5. Callback updates payment state.
6. Successful payment confirms the booking.
7. `BookingConfirmed` triggers ticket generation and email delivery.

### Showtime synchronization to Go service

Whenever a showtime is created, updated, or deleted:

1. The app emits `ShowtimeChanged`.
2. `SyncShowtimeToGo` runs as a queued listener.
3. The listener posts tenant, branch, and movie identifiers to `GO_SERVICE_URL`.

Related service repository:

- https://github.com/Ahmed-Wassim/go-cinema-sync

## Authentication, Authorization, And Locale Behavior

### Guards

- `web`: landlord session authentication using central users.
- `tenant`: JWT authentication using tenant users.

### Permissions

- Spatie permission tables exist in both central and tenant migration sets.
- `SpatiePermissionsBootstrapper` changes the permission cache key per tenant.

### Locale support

- Supported locales are `en`, `ar`, and `fr`.
- Landlord and tenant locale middleware are separated.
- The localization package is configured for session-based switching rather than URL prefixes.

### Currency handling

- `SetCurrentCurrency` middleware is appended globally.
- Current currency is shared with Blade views through `CurrentCurrencyService`.
- Tenant booking/payment logic stores currency on payments and bookings.

## API And UI Surface Summary

### Landlord UI

- Blade-based views in `resources/views/landlord`
- Auth pages, dashboard, users, tenants, plans, payments, suppliers, registration requests

### Public landing UI

- Blade landing view in `resources/views/home/landing.blade.php`
- Static assets under `public/home`

### Tenant dashboard API

Representative endpoints under `/api/dashboard` on tenant domains:

- `/auth/register`
- `/auth/login`
- `/users`
- `/branches`
- `/halls`
- `/seats`
- `/price-tiers`
- `/movies`
- `/showtimes`
- `/discounts`
- `/payments`
- `/tickets/validate`
- `/activity-logs`

### Tenant public API

Representative endpoints under `/api` on tenant domains:

- `/`
- `/movies/{id}`
- `/showtimes/{id}/seats`
- `/reserve-seats`
- `/bookings`
- `/checkout/initiate`
- `/checkout/callback`
- `/coupons/validate`
- `/booking/{id}/success`

## Project Structure

```text
app/
  Domain/
    Landlord/
    Shared/
    Tenant/
  Events/
  Http/
    Controllers/
    Middleware/
    Requests/
    Resources/
  Jobs/
  Listeners/
  Mail/
  Models/
  Policies/
  Providers/
  Tenancy/
config/
database/
  migrations/
  migrations/tenant/
  seeders/
docker/
lang/
public/
resources/views/
routes/
tests/
```

## Infrastructure And Deployment Notes

### Docker services

The provided `docker-compose.yml` defines:

- `app`: PHP-FPM application container
- `nginx`: web server
- `postgres`: PostgreSQL 16
- `redis`: Redis
- `mailhog`: local SMTP capture and web UI

### Container startup behavior

`docker/start.sh`:

- Ensures writable Laravel directories exist.
- Installs Composer dependencies if `vendor` is empty.
- Fixes permissions for `storage` and `bootstrap/cache`.
- Starts Supervisor.
- Starts PHP-FPM.

Supervisor runs:

- `queue:work`
- a looped `schedule:run`

### Scheduling

Configured in `bootstrap/app.php`:

- `SyncMoviesJob` every six hours
- `ReleaseReservedSeatsJob` every minute

## Local Setup

### Prerequisites

- Docker and Docker Compose
- Or a local PHP/PostgreSQL/Redis setup if you prefer running without Docker

### Recommended Docker setup

1. Copy environment variables:

```bash
cp .env.example .env
```

2. Update the important values in `.env`.

At minimum, verify:

- `APP_URL`
- `FRONTEND_URL`
- `LANDLORD_DOMAIN` because routes depend on it even though it is not present in `.env.example`
- `DB_CONNECTION=central` or another intended central connection for landlord runtime
- `DB_HOST=postgres`
- `DB_PORT=5432`
- `DB_DATABASE=cinema`
- `DB_USERNAME=root`
- `DB_PASSWORD=root`
- `REDIS_HOST=redis`
- `MAIL_HOST=mailhog`
- `PAYTABS_PROFILE_ID`
- `PAYTABS_SERVER_KEY`
- `PAYTABS_REGION`
- `TMDB_API_KEY`
- `GO_SERVICE_URL` if you want showtime sync enabled

3. Start the containers:

```bash
docker compose up --build -d
```

4. Generate the application key if needed:

```bash
docker compose exec app php artisan key:generate
```

5. Run central migrations:

```bash
docker compose exec app php artisan migrate --force
```

6. Seed central data if needed:

```bash
docker compose exec app php artisan db:seed
```

7. Access the services:

- Application via Nginx on `http://localhost`
- MailHog on `http://localhost:8025`
- PostgreSQL exposed on host port `5444`

### Tenant migration and seeding

Tenant migrations are configured through Stancl tenancy and are expected to run for tenant databases via tenancy commands or tenant lifecycle events.

Useful commands:

```bash
docker compose exec app php artisan tenants:migrate
docker compose exec app php artisan tenants:seed
```

## Useful Commands

```bash
php artisan route:list
php artisan migrate
php artisan tenants:migrate
php artisan tenants:seed
php artisan queue:work
php artisan schedule:run
php artisan test
```

There are also project-specific helper commands:

- `app/Console/Commands/TestMovieSyncCommand.php`
- `app/Console/Commands/TestShowtimeSync.php`

## Testing

The project uses Pest and includes feature and unit tests.

Current test coverage in the repository includes examples around:

- landlord subscription payment service
- shared payment manager
- tenant checkout payment service
- auth flows
- current currency middleware

Run tests with:

```bash
php artisan test
```

or:

```bash
composer test
```

## Notes About The Current Repository State

- The old README described only the landlord panel; this project is broader and includes tenant dashboard and tenant booking APIs as well.
- The tenant-facing frontend lives in a separate Next.js repository: https://github.com/Ahmed-Wassim/next-cinema
- That Next.js frontend is vibe coded.
- Showtime synchronization also depends on a separate Go service repository: https://github.com/Ahmed-Wassim/go-cinema-sync
- Composer scripts reference `npm install` and `npm run build`, but the current repository snapshot does not include a `package.json`. Frontend assets in this tree are primarily Blade views plus static files in `public/`.
- The Docker setup is the clearest supported local environment in the current codebase.
- Several files contain legacy comments or partially evolving abstractions, but the dominant architecture is a domain-layered Laravel monolith with multi-tenant boundaries.

## Reference Files

If you are onboarding into the codebase, these are good entry points:

- `composer.json`
- `bootstrap/app.php`
- `bootstrap/providers.php`
- `config/tenancy.php`
- `config/auth.php`
- `routes/web.php`
- `routes/landlord.php`
- `routes/tenant.php`
- `routes/tenant_home.php`
- `app/Providers/TenancyServiceProvider.php`
- `app/Domain/Tenant/Home/Booking/Services/Classes/BookingService.php`
- `app/Domain/Landlord/Billing/Payment/Services/SubscriptionPaymentService.php`
- `app/Domain/Tenant/Checkout/Payment/Services/Classes/OrderPaymentService.php`
- `app/Domain/Landlord/MovieSync/Classes/MovieSyncService.php`
