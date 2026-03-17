# Distro App Boilerplate

Reusable Laravel 12 boilerplate for admin-style applications built with Inertia, Vue, Fortify, Sanctum, and Spatie Permission.

## Stack

- Laravel 12
- PHP 8.5
- Inertia v2 + Vue 3 + TypeScript
- Laravel Fortify for auth
- Sanctum for API tokens
- Spatie Permission for RBAC
- Pest 4 for testing
- Wayfinder for typed frontend routes

## Current Baseline

- Auth flows:
  - login
  - registration
  - password reset
  - email verification
  - two-factor authentication
  - profile and security settings
- Admin shell:
  - sidebar
  - header actions
  - page header/container primitives
  - flash messages
- RBAC:
  - roles
  - permissions
  - role-based sidebar and route access
  - user role assignment
- Admin modules:
  - users CRUD
  - roles CRUD
- Cross-cutting modules:
  - notifications center
  - activity log
  - audit detail page
- API baseline:
  - `/api/v1/auth/*`
  - `/api/v1/notifications`
  - `/api/v1/activity-logs`
  - `/api/v1/admin/users`
  - `/api/v1/admin/summary`

## Local Setup

### Requirements

- PHP 8.5+
- Composer
- Node.js 22+
- npm
- SQLite, PostgreSQL, or MySQL

### Install

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
```

### Database

For SQLite:

```bash
touch database/database.sqlite
php artisan migrate --seed
```

For PostgreSQL or MySQL:

1. Update the database values in `.env`
2. Run:

```bash
php artisan migrate --seed
```

### Frontend

For local development:

```bash
npm run dev
```

If you are using Laravel Herd, open the Herd project URL in the browser. You do not need `php artisan serve`.

### Baseline Verification

```bash
php artisan test --compact
npm run types:check
npm run build
```

## Seeded Demo Accounts

After `php artisan migrate --seed`, these accounts are available:

- `admin@example.com` / `password`
- `manager@example.com` / `password`
- `member@example.com` / `password`
- `readonly@example.com` / `password`
- `external@example.com` / `password`
- `test@example.com` / `password`

## API Usage

The API uses Sanctum bearer tokens.

Main flow:

1. `POST /api/v1/auth/login`
2. Copy the returned bearer token
3. Call protected endpoints with `Authorization: Bearer <token>`

An importable Postman collection is included at [distro-app-api.postman_collection.json](/Users/yonassayfu/Herd/distro-app/distro-app-api.postman_collection.json).

## Operations Notes

### Queue

- Default queue driver: `database`
- For local async queue processing:

```bash
php artisan queue:listen --tries=1 --timeout=0
```

### Scheduler

There are no required scheduled tasks yet, but production deployments should still register Laravel's scheduler:

```bash
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```

### Mail

- Local default mailer is `log`
- For local inbox testing, switch `.env` to Mailpit or another SMTP sink

Example:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

### Deployment Baseline

At minimum, production should run:

- `php artisan migrate --force`
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`
- `npm run build`

And supervise:

- PHP/FPM or Octane
- queue worker
- scheduler

## CI

GitHub Actions verifies:

- PHP formatting with Pint
- frontend formatting
- ESLint
- TypeScript checks
- production asset build
- Pest test suite

## Project Workflow

- Use one branch per roadmap phase
- Keep `TheRoadmap/BoilerplateTaskList.md` in sync
- Record implementation learning in `TheRoadmap/laravelbasics.md`

## Useful Commands

```bash
composer setup
composer lint
composer lint:check
php artisan test --compact
npm run lint:check
npm run types:check
npm run build
```
