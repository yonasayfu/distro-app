# Production Readiness Guide

## Short answer

This boilerplate is deployable now, but it is not "production-finished for every hosting target" by default.

Current status:

- ready for serious deployment on Laravel Cloud
- ready for serious deployment on Laravel Forge-managed servers
- possible on GoDaddy VPS if you control PHP, queue workers, cron, and deployment commands
- not a good fit for basic shared hosting if you need queues, scheduler, and reliable background processing

## What is already production-capable

- Laravel 12 application structure
- Inertia + Vue production build
- authentication and RBAC foundation
- queue jobs table exists
- scheduler can be registered
- Sanctum API baseline exists
- CI baseline exists
- `.env.example` already includes the important environment keys

## What is still your responsibility before production

- set real production environment variables
- use a real mail provider
- run queue workers
- register the scheduler
- use HTTPS
- configure backups and database operations
- configure logs and monitoring
- run migrations safely during deployment
- confirm file storage strategy if future modules need uploads

## Production decision by platform

### Laravel Cloud

Best fit if you want the lowest operational overhead.

Why it fits this boilerplate:

- managed Laravel-first deployment flow
- easy environment variable management
- good fit for database-backed queues and scheduled tasks
- works well for standard Laravel production commands

What you should still configure:

- production database
- mail provider
- worker process
- scheduler
- `APP_URL`
- `SANCTUM_STATEFUL_DOMAINS` if browser-based API auth is used across domains

### Laravel Forge

Best fit if you want full server control without managing everything manually.

Why it fits this boilerplate:

- easy Nginx/PHP deployment
- proper worker and cron configuration
- better for custom server tuning than shared hosting

What you should configure in Forge:

- deployment script
- queue worker daemon
- scheduler cron
- SSL
- database credentials
- cache/session/queue strategy

### GoDaddy

This depends heavily on the product tier.

Use only if one of these is true:

- you have a VPS or dedicated server
- you can configure cron and long-running workers

Avoid standard shared hosting if you need this boilerplate intact.

Why shared hosting is a poor fit:

- queue workers are often not reliable or not allowed
- scheduler control is limited
- deployment process is usually manual and brittle
- modern Laravel operational expectations are harder to meet

## Minimum production checklist

### Environment

Set these correctly in production:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=...
DB_PORT=5432
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"

QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database
```

Review carefully if using browser-authenticated Sanctum across domains:

```dotenv
SANCTUM_STATEFUL_DOMAINS=your-domain.com,app.your-domain.com
SESSION_DOMAIN=.your-domain.com
```

### Build and deploy

Minimum deployment commands:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Runtime services

Production should run:

- web server and PHP-FPM
- one queue worker at minimum
- Laravel scheduler every minute

Example worker:

```bash
php artisan queue:work --tries=3 --timeout=90
```

Example cron:

```bash
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```

## Production readiness verdict

### Ready now

- internal admin tools
- role-based applications
- dashboard-style apps
- apps that need auth, RBAC, notifications, audit trails, API baseline

### Not complete by default

- backups
- observability and alerting
- object storage strategy
- heavy file upload flows
- horizontal scaling strategy
- high-volume mail throughput
- dedicated incident/recovery procedures

## Recommended deployment preference

For this boilerplate, prefer:

1. Laravel Cloud
2. Laravel Forge
3. GoDaddy VPS
4. avoid low-end shared hosting

## Before first production deploy

Run this locally first:

```bash
php artisan test --compact
npm run types:check
npm run build
```

Then confirm manually:

- admin login works
- role management works
- global search works
- notifications page works
- password reset email sends through real SMTP
- queue worker is processing jobs

## Final assessment

This boilerplate is strong enough to start real projects and deploy them. The codebase is not the blocker anymore. Operations discipline is the blocker.

That means:

- for Laravel Cloud or Forge: yes, this is in a practical deployment state
- for GoDaddy shared hosting: no, not without compromise
