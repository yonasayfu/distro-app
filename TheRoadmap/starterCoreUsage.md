# `starter-core` Usage Guidance

This file explains how to find, freeze, fetch, and reuse the `starter-core` boilerplate from this repository.

## Current Status

The intended stable reference for this boilerplate is:

- level name: `starter-core`
- stable release tag: `starter-core-v1`

The freeze work was completed through:

- `phase-18-release-readiness-closeout`
- `phase-19-starter-core-usage-guidance`

After release promotion, prefer the tag over any phase branch.

## Canonical Names

Use these names consistently:

- boilerplate level name: `starter-core`
- first stable tag name: `starter-core-v1`
- final freeze branch before release: `phase-18-release-readiness-closeout`

If you want the most stable reusable baseline later, the tag is what you should use.

## Recommended Final Release Flow

Run this once when you are ready to freeze the starter officially:

```bash
git checkout main
git pull
git merge --ff-only phase-18-release-readiness-closeout
composer validate --strict
php artisan test --compact tests/Feature/Auth/AuthenticationTest.php tests/Feature/Admin/PolicyConventionTest.php tests/Feature/Admin/PageCrudTest.php
npm run types:check
npm run build
git tag starter-core-v1
git push origin main
git push origin starter-core-v1
```

If your mainline branch is not `main`, replace it with the correct branch name.

## How to Find It Later

### Option 1: Use the release tag

This is the cleanest option when you want the frozen basic boilerplate:

```bash
git fetch --tags
git checkout starter-core-v1
```

Use this when:

- you want the exact frozen starter
- you do not want work-in-progress branches
- you want a stable base for a new project

### Option 2: Use the release branch

Use this only if you intentionally need the pre-tag freeze branch:

```bash
git fetch origin
git checkout phase-18-release-readiness-closeout
```

Use this when:

- the freeze is done but not tagged yet
- you want the latest release-closeout state before final tagging

### Option 3: Use `main`

Use this only after the freeze branch has been merged into `main`:

```bash
git checkout main
git pull
```

This is good for ongoing work, but not as precise as a named release tag.

## Best Practice for Starting a New Project

The safest workflow is:

1. start from the release tag
2. create a new project branch
3. rename the app
4. replace boilerplate branding and domain models gradually

Example:

```bash
git fetch --tags
git checkout starter-core-v1
git checkout -b project/acme-inventory
```

Then update:

- `.env`
- `APP_NAME`
- README title if needed
- public landing content
- seeded accounts if you want different defaults

## If You Want a New Repository From This Boilerplate

One practical flow is:

```bash
git clone <this-repo-url> acme-inventory
cd acme-inventory
git fetch --tags
git checkout starter-core-v1
git checkout -b main
git remote remove origin
git remote add origin <new-repo-url>
git push -u origin main
```

Then your new repository starts from the frozen `starter-core-v1` state.

## Which Reference Should You Use

Use this order:

1. `starter-core-v1` tag
2. `phase-18-release-readiness-closeout` branch
3. `main` only after the release branch is merged

That order avoids ambiguity.

## What To Tell Yourself Later

If you come back months later, remember:

- `starter-core` is the name of the basic reusable boilerplate
- `starter-core-v1` is the first stable frozen version
- `starter-business` and `starter-enterprise` are later levels, not this one

## Required Docs To Read Before Reusing It

Read these in order:

1. `TheRoadmap/starterCoreV1.md`
2. `TheRoadmap/starterCoreUsage.md`
3. `TheRoadmap/BoilerplateTaskList.md`
4. `TheRoadmap/followTemplate.md`
5. `TheRoadmap/laravelbasics.md`

## Short Rule

If you want the easiest reusable version later, fetch the `starter-core-v1` tag and branch from it.
