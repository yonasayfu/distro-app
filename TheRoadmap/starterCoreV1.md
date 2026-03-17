# `starter-core-v1` Freeze Plan

## Goal

Define the exact release boundary for the first stable `starter-core` tag.

This document answers one question:

- what must be true before this repository can be tagged as `starter-core-v1`

It also answers the opposite question:

- what should not be added before the first `starter-core` release

## What `starter-core` means

`starter-core` is the clean, domain-neutral Laravel boilerplate layer.

It should solve:

- authentication
- authorization
- admin shell
- user and role management
- shared CRUD patterns
- notifications
- activity logs
- API baseline
- deployment and setup baseline
- public landing and handbook

It should not solve domain business logic.

## What is already complete

The current repository already includes these completed areas:

- app shell and grouped navigation
- Fortify auth flows and account settings baseline
- RBAC foundation with seeded roles and demo users
- admin `Users` and `Roles` modules
- shared CRUD toolbar, table, pagination, confirmation, and flash patterns
- notification center
- activity log index and detail pages
- versioned Sanctum API baseline
- Postman collection and CI baseline
- public landing page
- handbook/learning center
- roadmap and workflow documentation

## What must still be completed before `starter-core-v1`

These are the remaining freeze items.

### 1. Auth and security review closure

Even though the auth system exists, the tracker still leaves Phase 2 partially open.

Required closeout:

- confirm login, register, logout, forgot password, reset password, verify email, and 2FA remain intentionally in scope
- review styling consistency of auth screens against the newer public/app experience
- confirm Fortify rate limiting and middleware choices
- mark the tracker based on what is already implemented versus what still needs polish

Why:

- a starter cannot be tagged stable while the auth phase still looks unfinished in its own tracker

### 2. Policy conventions for admin modules

Permissions already protect routes and controllers, but the boilerplate should document and demonstrate a consistent policy layer for admin-managed resources.

Required closeout:

- define policy usage rule for future modules
- add at least one clear policy example if missing
- document the pattern in the tracker and learning notes

Why:

- future modules should not guess when to use permissions, policies, or both

### 3. Shared CRUD remaining wrappers

The CRUD foundation is strong, but a few shared pieces are still marked incomplete.

Required closeout:

- reusable form section wrapper
- zero-state and empty-state reuse review
- loading skeleton review for list and form pages

Why:

- `starter-core-v1` should show one repeatable CRUD pattern, not mostly-repeatable patterns

### 4. Public pages module minimum cut

The public website phase exists, but content management is not yet complete.

Required closeout:

- create `pages` content model
- add admin CRUD for public pages
- add slug-based public page routes
- add publish/draft protection
- add basic SEO fields
- add focused tests for public visibility and draft protection

Why:

- without this, the public side is still mostly hardcoded
- a reusable starter should support at least one backend-managed public content flow

### 5. Release readiness checklist

Before tagging `starter-core-v1`, do a final release closeout.

Required closeout:

- review `.env.example`
- confirm README install steps still match reality
- confirm seeded accounts and role docs are accurate
- confirm CI still reflects the current commands
- define tag instructions in `gitguidance.md`

Why:

- a boilerplate release should be installable from docs without tribal knowledge

## What is intentionally deferred to `starter-business`

Do not block `starter-core-v1` on these items:

- generic settings system
- media/file foundation
- notes/comments layer
- status/workflow foundation
- import foundation
- dashboard widget library
- broader reporting patterns
- soft-delete and restore conventions

These belong to `starter-business`, not `starter-core`.

## What is intentionally deferred to `starter-enterprise`

Do not block `starter-core-v1` on these items:

- approval workflow foundation
- advanced audit depth
- localization scaffold
- organization profile layer
- currency/date/number helpers
- stronger reporting architecture
- queue-heavy operational patterns
- tenancy-ready constraints

These belong to `starter-enterprise`.

## Freeze decision rule

A feature may still enter `starter-core-v1` only if all of the following are true:

- it is useful in almost every project
- it does not introduce domain coupling
- it is not substantially easier to add in `starter-business`
- it does not delay the first stable release unnecessarily

If any of those fail, defer it.

## Proposed release sequence

1. close the remaining freeze items
2. mark the tracker items complete
3. run the final targeted test pass
4. create and push the tag `starter-core-v1`
5. branch to `level/starter-business`

## Recommended release statement

When this freeze is complete, the repository should be described as:

`starter-core-v1` is a reusable Laravel 12 + Inertia + Vue boilerplate with auth, RBAC, admin CRUD conventions, notifications, audit logging, API baseline, public landing, and handbook support.

## Success condition

`starter-core-v1` is ready when:

- core platform features are complete
- the remaining open tracker items are either finished or explicitly deferred
- a new project can clone the repository and understand what belongs in the base versus higher starter levels
