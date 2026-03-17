# Talk With AI Guidance

This file is the handoff prompt and working guidance for any AI agent that continues development on this boilerplate.

Use this when switching between Codex, Gemini, Claude, Cursor agents, or any other coding assistant.

## 1. Short Answer To Give Any AI First

Use this first prompt:

```text
This repo is not a fresh Laravel starter anymore. It is a reusable Laravel 12 boilerplate with Inertia Vue, Fortify, Sanctum, Spatie Permission, notifications, activity logs, admin CRUD, API baseline, export foundation, and global search.

Before making changes, read these files in this order:

1. TheRoadmap/BoilerplateRoadmap.md
2. TheRoadmap/BoilerplateTaskList.md
3. TheRoadmap/followTemplate.md
4. TheRoadmap/guidanceIntergartion.md
5. TheRoadmap/gitguidance.md
6. TheRoadmap/laravelbasics.md

Then inspect the actual code before changing anything.

Important rules:
- preserve the existing boilerplate architecture
- use the shared CRUD, RBAC, notification, audit, API, export, and search patterns already in the repo
- every new module must use permissions
- every meaningful action should be tested
- after each completed task, update the relevant roadmap docs
- keep laravelbasics.md updated with before/after/why explanations
- commit and push logical milestones on the current phase branch
```

## 2. Why This Prompt Matters

Without this context, another AI may do the wrong things:

- treat the repo like a default starter kit
- bypass RBAC patterns
- add hardcoded sidebar items
- skip activity logging
- create inconsistent CRUD pages
- ignore the roadmap and duplicate old work

This prompt prevents that.

## 3. Required File Reading Order

Every AI should refer to these files in this order:

### 1. Roadmap

- [BoilerplateRoadmap.md](/Users/yonassayfu/Herd/distro-app/TheRoadmap/BoilerplateRoadmap.md)

Purpose:

- understand the overall target
- understand what is in scope and out of scope

### 2. Task tracker

- [BoilerplateTaskList.md](/Users/yonassayfu/Herd/distro-app/TheRoadmap/BoilerplateTaskList.md)

Purpose:

- know what is already done
- know what is still pending

### 3. Pattern guide

- [followTemplate.md](/Users/yonassayfu/Herd/distro-app/TheRoadmap/followTemplate.md)

Purpose:

- follow the UI and implementation patterns already chosen

### 4. Integration guide

- [guidanceIntergartion.md](/Users/yonassayfu/Herd/distro-app/TheRoadmap/guidanceIntergartion.md)

Purpose:

- know how to add a new entity, module, page, permission, search result, export, and API correctly

### 5. Git workflow

- [gitguidance.md](/Users/yonassayfu/Herd/distro-app/TheRoadmap/gitguidance.md)

Purpose:

- know the branch and commit expectations

### 6. Learning log

- [laravelbasics.md](/Users/yonassayfu/Herd/distro-app/TheRoadmap/laravelbasics.md)

Purpose:

- understand why earlier code changed
- see before/after patterns

## 4. What The AI Must Understand About This Boilerplate

Tell the AI these facts:

- the boilerplate is `users`-centric
- RBAC is first-class, not optional
- sidebar visibility must match backend permission checks
- Users and Roles pages are the canonical CRUD examples
- notifications and activity logs are reusable platform features
- API routes under `/api/v1` are part of the platform
- export/print and global search are already implemented as reusable foundations
- setup, CI, and env defaults are part of the product quality, not afterthoughts

## 5. What The AI Should Never Do

Do not let an AI:

- remove or bypass permission middleware
- add UI items without permission mapping
- invent a second CRUD style
- add domain-specific naming into the boilerplate core
- skip tests
- skip updating `laravelbasics.md` after meaningful work
- skip roadmap/task updates
- make destructive git changes unless explicitly told

## 6. Recommended Prompt For Adding A New Module

Use this prompt:

```text
I want to add a new module to this boilerplate. Before changing code:

1. Read:
- TheRoadmap/BoilerplateRoadmap.md
- TheRoadmap/BoilerplateTaskList.md
- TheRoadmap/followTemplate.md
- TheRoadmap/guidanceIntergartion.md
- TheRoadmap/gitguidance.md
- TheRoadmap/laravelbasics.md

2. Follow the existing boilerplate architecture.

3. For the new module:
- add permissions
- update the permission seeder
- add route middleware
- add sidebar integration if needed
- use shared CRUD components
- add activity logging if the action is important
- add API support only if the module needs it
- add tests

4. After implementation:
- update TheRoadmap/BoilerplateTaskList.md
- update TheRoadmap/laravelbasics.md with before/after/why notes
- commit and push the branch
```

## 7. Recommended Prompt For Reviewing Existing Work

Use this prompt:

```text
Review this repo as a reusable Laravel boilerplate, not as a generic app.

Read first:
- TheRoadmap/BoilerplateRoadmap.md
- TheRoadmap/BoilerplateTaskList.md
- TheRoadmap/followTemplate.md
- TheRoadmap/guidanceIntergartion.md

Then review the changed files with focus on:
- RBAC correctness
- route and sidebar consistency
- CRUD pattern consistency
- notification and activity-log integration
- API consistency
- missing tests
- regressions against the boilerplate patterns
```

## 8. Recommended Prompt For Continuing The Roadmap

Use this prompt:

```text
Continue from the current boilerplate roadmap.

First read:
- TheRoadmap/BoilerplateRoadmap.md
- TheRoadmap/BoilerplateTaskList.md
- TheRoadmap/followTemplate.md
- TheRoadmap/gitguidance.md
- TheRoadmap/laravelbasics.md

Then identify:
- current phase
- completed tasks
- next logical batch

Implement the next batch using the existing patterns, run the minimum relevant tests, update the docs, and commit the result.
```

## 9. If You Need To Brief The AI In One Paragraph

Use this:

```text
This repo is a structured Laravel 12 reusable boilerplate, not a default starter. It already includes auth, 2FA, RBAC, admin users/roles, shared CRUD patterns, notifications, activity logs, API baseline, export/print foundation, global search, and setup/CI docs. Any new work must preserve these patterns, use permissions consistently, update the roadmap docs, update laravelbasics.md with before/after/why notes, and add tests.
```

## 10. What Information Is Most Valuable To Share With Any AI

Always share:

- the exact goal for the next task
- the current phase or target file
- whether the work is new feature, refactor, fix, or review
- whether docs should be updated
- whether commit/push is expected

If the task is module-related, also share:

- the module name
- the entity names
- expected permissions
- whether it needs API support
- whether it needs notifications
- whether it needs export/search integration

## 11. Final Rule

Any AI working in this repo should think like this:

- this is a platform
- not a quick one-off app

That single mindset should drive all design and code decisions.
