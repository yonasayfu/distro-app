# Mail Testing Guide

## Goal

Use local Mailpit to confirm that Laravel auth and notification emails are actually leaving the application and can be reviewed in a local inbox.

## Current mail status in this boilerplate

- default local mailer in `.env.example` is `log`
- email-related auth flows already exist
- auth/email tests pass for:
  - verification notification
  - email verification
  - password reset

This means the application logic is in place. You only need to point Laravel to a real local SMTP sink such as Mailpit.

## Mailpit local setup

If Mailpit is installed locally, use these `.env` values:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=no-reply@distro-app.test
MAIL_FROM_NAME="${APP_NAME}"
```

Then clear cached config:

```bash
php artisan config:clear
```

## How to open Mailpit

Mailpit usually exposes:

- SMTP: `127.0.0.1:1025`
- inbox UI: `http://127.0.0.1:8025`

If your local Mailpit setup uses different ports, use those instead.

## Best manual email tests

### 1. Password reset email

This is the cleanest first test.

Steps:

1. open the login page
2. click `Forgot password`
3. submit an existing user email like `admin@example.com`
4. open Mailpit and confirm the reset email arrived

Why this is best:

- it does not require changing seeded accounts
- it proves real SMTP delivery into Mailpit

### 2. Email verification email

Use this if email verification is enabled in your flow.

Steps:

1. create a new user or use an unverified one
2. trigger `Resend verification email`
3. check Mailpit for the verification message

### 3. Future module email tests

When you later add new mail features, test them the same way:

- trigger feature in browser or API
- open Mailpit
- inspect subject, sender, body, and links

## Important local caveats

### Queue behavior

If future emails become queued, you must also run a worker locally:

```bash
php artisan queue:work
```

For the current auth email flows, Mailpit testing is usually enough with the SMTP config alone, but queued notifications later will require a running worker.

### Config cache

If Mailpit does not receive anything after changing `.env`, run:

```bash
php artisan config:clear
```

If needed, also run:

```bash
php artisan optimize:clear
```

## What to test in each message

Check these in Mailpit:

- sender address
- sender name
- subject line
- link URL uses the correct app domain
- message body is readable
- no broken buttons or malformed URLs

## Production note

Mailpit is only for local validation.

For real deployment, replace it with:

- Postmark
- Resend
- Mailgun
- SES
- SMTP from your provider

## Recommended workflow

For every future project built on this boilerplate:

1. configure Mailpit locally
2. test password reset
3. test verification email
4. later test each new module-specific mail flow
5. before production, swap to a real provider and retest
