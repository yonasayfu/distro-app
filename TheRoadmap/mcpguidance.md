# MCP Guidance

This document explains how MCP-style project tooling helps inside this Laravel project, how it helps me as the coding agent, how it helps you as the developer, and how to get better at using it.

## What MCP Means Here

In this project, MCP is the tooling layer that gives structured project-aware access to useful development capabilities.

From the current project config in `boost.json`, this repo has MCP-related support enabled:

- general MCP support
- Herd MCP support
- Laravel Boost support through the installed package

## What It Helps Me With

When MCP tools are available, they help me work faster and with less guessing.

Typical useful capabilities in a Laravel project are:

- searching version-specific Laravel docs
- reading browser logs
- checking routes
- inspecting database schema
- reading from the database
- generating project URLs correctly
- understanding package-aware project conventions

## What It Helps You With

For you as a learner and maintainer, MCP-style tooling can help you:

- inspect how Laravel is wired without manually digging everywhere
- verify routes, configs, schema, and environment details faster
- get project-aware docs instead of generic internet answers
- debug local issues using browser, database, and route context
- learn Laravel by seeing the exact structure of the current app

## What Is Relevant in This Project

### 1. Laravel Boost

This project includes `laravel/boost` and has `boost.json`.

That means the app is intended to support Laravel-aware development tooling such as:

- Laravel-specific guidance
- package-aware documentation search
- local project conventions

Why it matters:

- Laravel has many valid ways to build something
- Boost-oriented tooling helps stay aligned with the installed version and packages

### 2. Herd MCP

This project is running under Laravel Herd conventions.

Why that matters:

- the app URL is predictable under Herd
- local development assumptions differ from plain `php artisan serve`
- project-local URLs and environment setup are easier to reason about

### 3. Project-Aware Tooling

The useful mindset is this:

- do not guess routes when you can inspect them
- do not guess schema when you can inspect it
- do not guess docs when you can search version-specific docs

That is where MCP-style workflows become valuable.

## Practical Laravel Commands You Should Master

Even when MCP is available, you should still know the base Laravel commands:

```bash
php artisan list
php artisan route:list
php artisan config:show app
php artisan test --compact
php artisan migrate:status
php artisan tinker
```

These commands teach you the framework while also helping you debug quickly.

## Practical Ways MCP and Laravel Work Together

### Documentation

Use project-aware docs first when:

- you are unsure of Laravel 12 behavior
- you are working with Fortify
- you are working with Inertia
- you are working with Pest
- you are working with Wayfinder

### Database

Use schema or query tooling when:

- you need to understand a table before writing a migration
- you need to verify a relationship assumption
- you need to inspect seeded or runtime data safely

### Routes

Use route inspection when:

- a frontend link seems wrong
- a Wayfinder route looks odd
- middleware behavior is unclear

### Browser and Frontend Debugging

Use browser log tooling when:

- an Inertia page fails silently
- a Vue component throws at runtime
- a route works on the backend but fails in the browser

## How to Master MCP in This Project

Use this progression:

### Level 1: Observe

Before changing code, inspect:

- routes
- middleware
- config
- models
- requests
- frontend page component

### Level 2: Verify

After changing code, verify with:

- tests
- browser behavior
- route checks
- database or schema checks when needed

### Level 3: Compare

When Laravel offers multiple ways to build something:

- compare the local project pattern
- compare official docs
- choose the Laravel-native and project-consistent approach

### Level 4: Reuse

Once a pattern is proven:

- document it
- reuse it
- avoid reinventing the structure in every module

## What You Should Ask Me To Do With MCP

Good requests:

- check which routes exist for a feature
- inspect the schema before changing a model
- verify why a page is failing in the browser
- look up the correct Laravel 12 way to implement a feature
- compare project structure against docs before refactoring

## Limits to Remember

MCP does not replace understanding.

It helps with:

- context
- speed
- precision
- verification

But you still need to learn:

- routing
- middleware
- requests
- controllers
- models
- policies
- migrations
- testing

## Current Project Advice

For this project, the best use of MCP is:

1. route and middleware inspection
2. Laravel and package-specific docs
3. schema inspection before writing new database work
4. browser/runtime debugging during Inertia work
5. environment-aware URL handling under Herd

## What I Will Do Going Forward

As we work through tasks, I will use project-aware inspection and verification where it helps, and I will also explain the Laravel concept behind the step in `laravelbasics.md`.
