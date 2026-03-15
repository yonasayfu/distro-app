# Laravel Basics

This is the running learning log for this project.

Rule for future work:

- after each implemented task or meaningful batch of work, add a short entry here
- explain what was changed
- explain which Laravel feature or package we used
- explain why that approach fits Laravel

The goal is not just to finish the boilerplate. The goal is to understand how Laravel applications are structured and why the code is written that way.

## How to Use This File

For every completed task, record:

1. What we built
2. Which Laravel feature or package we used
3. Which files were important
4. Why the implementation was done this way
5. What to remember for future projects

## Current Project Stack

This starter currently uses:

- Laravel 12
- Inertia.js
- Vue 3
- Fortify
- Wayfinder
- Pest
- Pint
- Boost

## Laravel Structure in This Repo

Important folders:

- `app/`: application logic such as controllers, models, middleware, providers, requests, and actions
- `routes/`: route definitions
- `resources/js/`: Inertia Vue frontend
- `config/`: framework and package configuration
- `database/`: migrations, factories, and seeders
- `tests/`: Pest tests

Important project files:

- `bootstrap/app.php`: application bootstrapping, middleware registration, and routing setup in Laravel 12
- `routes/web.php`: browser routes
- `routes/settings.php`: profile, security, and appearance routes
- `app/Http/Middleware/HandleInertiaRequests.php`: shared data passed from Laravel to Inertia pages
- `app/Providers/FortifyServiceProvider.php`: auth view wiring and rate limiting

## Key Laravel Concepts Already Present

### 1. Routes

Routes map URLs to controllers or Inertia pages.

Examples in this repo:

- `/` returns the welcome page through Inertia
- `/dashboard` returns the dashboard page for authenticated and verified users
- `/settings/...` handles profile, security, and appearance

What to remember:

- routes define the public shape of your app
- middleware on routes controls access
- named routes make navigation more maintainable

### 2. Middleware

Middleware sits between the request and the response.

Examples already used:

- `auth`
- `verified`
- custom Inertia-sharing middleware

What to remember:

- use middleware for cross-cutting rules like authentication, verification, throttling, and shared request behavior

### 3. Controllers

Controllers handle request logic.

In this repo the settings pages are handled by controllers like:

- `ProfileController`
- `SecurityController`

What to remember:

- keep controllers thin
- move validation to Form Requests
- move complex business logic to services or action classes when needed

### 4. Fortify

Fortify provides backend auth features without forcing Blade UI.

In this repo it powers:

- login
- register
- password reset
- email verification
- two-factor authentication

What to remember:

- Fortify handles auth mechanics
- Inertia pages provide the frontend
- `FortifyServiceProvider` connects Fortify routes to your Vue pages

### 5. Inertia

Inertia lets Laravel return Vue pages without building a separate SPA API for everything.

What to remember:

- Laravel remains the routing and backend source of truth
- Vue renders the page component
- shared props can be added through `HandleInertiaRequests`

### 6. Wayfinder

Wayfinder generates route helpers for TypeScript so frontend links and form actions stay aligned with Laravel routes.

What to remember:

- it reduces hardcoded frontend URLs
- it helps keep backend and frontend route usage consistent

### 7. Pest

Pest is the testing framework used in this project.

What to remember:

- feature tests are usually the most useful in Laravel apps
- tests prove behavior, not just syntax

## Entry 001: Phase 0 Documentation Setup

### What we did

We created the standalone planning folder `TheRoadmap/` and added the roadmap, task list, UI template guide, reference carryover notes, MCP guidance, git workflow guidance, and this Laravel learning file.

### Laravel concepts involved

- project structure
- package awareness
- environment and setup readiness
- architecture planning before code changes

### Why this matters

A Laravel boilerplate is not only code. It is also conventions, workflow, and repeatable structure. If the planning layer is weak, the codebase drifts quickly.

### Files involved

- `TheRoadmap/README.md`
- `TheRoadmap/BoilerplateRoadmap.md`
- `TheRoadmap/BoilerplateTaskList.md`
- `TheRoadmap/followTemplate.md`
- `TheRoadmap/ReferenceCarryover.md`
- `TheRoadmap/mcpguidance.md`
- `TheRoadmap/gitguidance.md`
- `TheRoadmap/laravelbasics.md`

### What to remember

- before building modules, define the app shape
- keep the starter domain-neutral
- write down why something exists so future work stays consistent
