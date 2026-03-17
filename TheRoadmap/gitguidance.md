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

Current active phase branch:

- `phase-10-export-search`

Current phase purpose:

- add optional but reusable platform extras: permission-aware global search plus the first export and print foundation

## Important Security Note

Do not keep personal access tokens embedded in git remotes long term.

Prefer:

- credential manager
- SSH remote
- GitHub CLI auth

This keeps repository access safer on your machine.
