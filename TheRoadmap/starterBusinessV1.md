# `starter-business-v1` Freeze Plan

## Goal

Define the exact finish line for the first stable `starter-business` release.

Status:

- freeze checklist complete
- ready for release promotion and tag creation as `starter-business-v1`

`starter-business` must remain:

- above `starter-core`
- below domain starters

## What `starter-business` means

`starter-business` is the reusable business-foundation layer.

It should solve:

- settings
- files and attachments
- notes/comments
- workflow status conventions
- import baseline
- restore conventions
- dashboard/reporting primitives

It should not solve:

- product management
- inventory movement
- payroll
- HR recruitment
- accounting
- procurement
- trading or finance-specific domain flows

## Required Inputs

This level assumes the project already starts from:

- `starter-core-v1`

That means `starter-business` should extend the stable tag, not redesign it.

## Required Freeze Items

Before tagging `starter-business-v1`, these should be complete:

### 1. Settings

- reusable settings storage
- admin settings UI
- organization profile support

### 2. Files

- upload validation
- reusable attachment pattern
- basic file listing/downloading

### 3. Notes

- reusable notes/comments behavior
- permissions and tests

### 4. Workflow Basics

- status vocabulary
- badge UI
- transition pattern

### 5. Import and Restore

- CSV import baseline
- import validation/preview
- soft delete and restore guidance

### 6. Reporting Base

- stat cards
- report table pattern
- export hook points
- dashboard and report tests

### 7. Release Readiness

- update README or level docs if needed
- update task tracker
- update learning archive
- define tag instructions for `starter-business-v1`

All release-readiness items are now complete.

## Deferral Rules

Do not block `starter-business-v1` on:

- domain entities such as products, employees, warehouses, or contracts
- domain approval chains
- tenant architecture
- localization or enterprise-scale reporting

Those belong in later levels.

## Recommended Branch and Tag Names

- branch: `level/starter-business`
- first stable tag: `starter-business-v1`

## Verification Before Tagging

Run:

```bash
composer validate --strict
php artisan test --compact tests/Feature/Admin/SettingsManagementTest.php tests/Feature/Admin/MediaManagementTest.php tests/Feature/Admin/NoteManagementTest.php tests/Feature/Admin/PageStatusWorkflowTest.php tests/Feature/Admin/PageImportTest.php tests/Feature/DashboardWidgetsTest.php tests/Feature/ReportsIndexTest.php
npm run types:check
npm run build
```

Then promote and tag:

```bash
git checkout main
git pull
git merge --ff-only level/starter-business-release-readiness
git push origin main
git tag starter-business-v1
git push origin starter-business-v1
```

## Success Condition

`starter-business-v1` is ready when:

- common business foundations are stable
- future projects can add their own modules without rebuilding settings/files/workflow basics
- the codebase still feels modular and domain-neutral

That condition is now met.
