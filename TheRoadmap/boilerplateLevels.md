# Boilerplate Levels Strategy

## Goal

Define the boilerplate as a set of clear levels instead of one ever-growing starter.

This keeps the base clean and lets future projects start from the right level without stripping out features later.

## Recommended level names

Use these names instead of placeholder names like `_aaa`:

- `starter-core`
- `starter-business`
- `starter-enterprise`

These are generic enough to reuse across different kinds of projects and still clear enough to understand immediately.

## Level 1: `starter-core`

This is the cleanest reusable base.

It should contain:

- auth and security
- RBAC
- admin shell
- users and roles management
- shared CRUD patterns
- notifications
- activity logs
- API baseline
- operations and setup docs
- public landing page
- handbook

This level is for:

- small to medium internal tools
- admin portals
- dashboards
- public-plus-admin starter projects

## Level 2: `starter-business`

This builds on `starter-core` and adds reusable business features that many systems need.

It should add:

- public pages module
- generic settings system
- media/file foundation
- status and workflow pattern
- notes/comments pattern
- import/export foundation
- reusable dashboard widgets
- reporting base patterns
- soft delete and restore conventions

This level is for:

- ecommerce back-office systems
- inventory management
- organization automation
- ERP-like operational systems
- HR and admin-heavy systems

## Level 3: `starter-enterprise`

This builds on `starter-business` and adds organization-scale foundations.

It should add:

- approval workflow foundation
- stronger audit history
- localization scaffold
- currency/date/number formatting helpers
- organization profile and settings
- queue-heavy background job patterns
- stronger reporting structure
- tenancy-ready design constraints

This level is for:

- ERP
- enterprise operations
- multi-department systems
- finance-heavy or policy-heavy systems

## Domain starters later

Do not put domain logic in the shared levels unless it is cross-project.

If needed later, create domain-level starters such as:

- `starter-ecommerce`
- `starter-inventory`
- `starter-erp`
- `starter-hr`

These should sit on top of the shared levels, not inside them.

## Current repo mapping

The current repository should be treated as:

- current target level: `starter-core`

Why:

- most of the implemented work so far is platform foundation, not domain business logic
- that makes it the correct place to freeze and stabilize first

## What is still missing before `starter-core` is truly complete

Recommended remaining `starter-core` work:

- complete public `pages` module
- finish publish/draft public routing
- finish basic SEO fields
- improve auth review checklist completion in the tracker
- tighten policy conventions for admin modules
- complete a few remaining shared CRUD wrappers

## What should wait for `starter-business`

Do not add these to `starter-core`:

- full settings system
- file/media attachment system
- notes/comments layer
- status/workflow foundation
- import foundation
- dashboard/report widget library

Those belong to `starter-business`.

## Branch and tag strategy

Recommended progression:

### Branches

- `level/starter-core`
- `level/starter-business`
- `level/starter-enterprise`

### Tags

- `starter-core-v1`
- `starter-business-v1`
- `starter-enterprise-v1`

## Repository strategy

Recommended approach:

1. keep one repository now
2. stabilize levels through branches and tags
3. only split into separate repositories if maintenance becomes painful

Why:

- one repo keeps shared fixes easier
- levels are still traceable
- you avoid cloning and maintaining multiple repos too early

## GitHub publishing guidance

If you later want separate repos, use names like:

- `starter-core`
- `starter-business`
- `starter-enterprise`

But do not create them yet unless one of these becomes true:

- the branches diverge too much
- release cadence differs heavily
- maintaining one repo becomes confusing

## Recommended next sequence

1. finish and freeze `starter-core`
2. tag it as `starter-core-v1`
3. branch upward into `level/starter-business`
4. implement business-level shared modules there
5. only after that define `starter-enterprise` implementation in detail

## Decision rule

When evaluating a feature, ask:

- is this needed by almost every project?
  - if yes, it may belong in `starter-core`
- is this needed by many business systems but not all simple projects?
  - if yes, it belongs in `starter-business`
- is this needed mostly by large or process-heavy systems?
  - if yes, it belongs in `starter-enterprise`
- is this specific to one industry?
  - if yes, keep it out of shared levels
