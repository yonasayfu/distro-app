# Git Guidance

This file defines the git workflow for this boilerplate project.

The goal is to keep each phase traceable, reviewable, and easy to restore later.

## Working Rules

### 1. One Phase, One Branch

Each major phase should have its own branch.

Suggested branch pattern:

- `phase-0-roadmap-docs`
- `phase-1-app-shell`
- `phase-2-auth-polish`
- `phase-3-rbac-foundation`
- `phase-4-rbac-management`
- `phase-5-admin-crud`
- `phase-5-5-api-foundation`
- `phase-7-notifications-activity`
- `phase-8-api-baseline`
- `phase-9-dx-operations`
- `phase-10-export-search`
- `phase-10-integration-guides`
- `phase-10-shell-cleanup-search-fix`
- `phase-11-deployment-mail-guides`
- `phase-11-search-ux`
- `phase-12-public-website-planning`
- `phase-12-public-website-foundation`
- `phase-12-handbook-module`
- `phase-12-public-handbook-access`
- `phase-13-boilerplate-levels-planning`
- `phase-13-starter-core-freeze`
- `phase-14-public-pages-module`
- `phase-15-auth-review-closeout`
- `phase-16-policy-conventions`
- `phase-17-crud-wrappers`

### 2. Commit at Logical Milestones

Do not wait too long to commit.

Commit when:

- a task batch is complete
- tests pass for that batch
- docs are updated for that batch

### 3. Keep Task Tracking in Sync

After completing work:

- update `TheRoadmap/BoilerplateTaskList.md`
- append a short learning note to `TheRoadmap/laravelbasics.md`
- mention what changed in the commit message

### 4. Push Frequently

Push after a clean task batch or at least after each meaningful checkpoint in a phase.

That makes the work recoverable and easier to review.

## Recommended Commit Message Style

Use concise commit messages:

- `docs: add roadmap, MCP, and git guidance`
- `feat: replace starter shell with admin layout`
- `feat: add RBAC seeders and permission gating`
- `test: add auth and RBAC feature coverage`
- `refactor: centralize sidebar navigation config`

## Recommended Workflow Per Phase

### Start a Phase

```bash
git checkout main
git pull
git checkout -b phase-x-name
```

If `main` is not your mainline branch, replace it with your actual base branch.

### During the Phase

```bash
git status
git add .
git commit -m "your message"
git push -u origin phase-x-name
```

### End of the Phase

- verify tests
- update docs
- push the latest branch
- decide whether to merge or keep the branch as a milestone snapshot

## What I Will Do

Going forward, I should:

- create a branch when starting a new phase
- remind you when a commit is due
- update roadmap docs when work completes
- commit logical work when requested or when that workflow is clearly expected

## What You Should Review Before Merge

Before considering a phase complete, review:

- code changes
- test results
- `BoilerplateTaskList.md`
- `laravelbasics.md`

## Current Phase Record

Branch history so far:

- `phase-0-roadmap-docs`
- `phase-1-app-shell`
- `phase-3-rbac-foundation`
- `phase-5-admin-crud`
- `phase-5-5-api-foundation`
- `phase-7-notifications-activity`
- `phase-8-api-baseline`
- `phase-9-dx-operations`
- `phase-10-export-search`
- `phase-10-integration-guides`
- `phase-10-shell-cleanup-search-fix`
- `phase-11-deployment-mail-guides`
- `phase-11-search-ux`
- `phase-12-public-website-planning`
- `phase-12-public-website-foundation`
- `phase-12-handbook-module`
- `phase-12-public-handbook-access`
- `phase-13-starter-core-freeze`
- `phase-14-public-pages-module`
- `phase-15-auth-review-closeout`
- `phase-16-policy-conventions`
- `phase-17-crud-wrappers`
- `phase-18-release-readiness-closeout`
- `phase-19-starter-core-usage-guidance`
- `level/starter-business-planning`
- `level/starter-business-settings-foundation`
- `level/starter-business-pwa-guidance`
- `level/starter-business-media-foundation`
- `level/starter-business-notes-layer`
- `level/starter-business-status-workflow`

Current active phase branch:

- `level/starter-business-status-workflow`

Current phase purpose:

- implement the fourth `starter-business` slice through the shared status and workflow foundation

## Tagging `starter-core-v1`

Once the freeze checklist is complete, use this release flow:

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

## Important Security Note

Do not keep personal access tokens embedded in git remotes long term.

Prefer:

- credential manager
- SSH remote
- GitHub CLI auth

This keeps repository access safer on your machine.
