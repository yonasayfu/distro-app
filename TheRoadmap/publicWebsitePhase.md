# Public Website Foundation Phase

## Goal

Add a public-facing website layer to the boilerplate so future projects can support both:

- a polished guest-facing website
- an authenticated admin/application backend

This phase is not about mixing the public website into the admin shell. It is about creating a separate frontend surface with a clean connection to backend-managed content.

## Why this phase matters

Many real projects need two experiences:

- public website for visitors, customers, or prospects
- private app/admin area for operators

Without a public website foundation, each new project has to rebuild:

- landing page structure
- guest layout
- page content management
- publish/draft handling
- public navigation and footer

This phase turns the boilerplate into something closer to a real multi-surface product foundation.

## Core design rule

Keep the public website and the internal app shell separate.

That means:

- public pages should not reuse the admin sidebar shell
- public pages should have their own layout, spacing, navigation, and footer
- admin users should manage content from the backend, not from inline editing on the public side

## Scope

### Include

- public landing page
- separate public layout
- guest navigation
- hero, feature, CTA, and footer sections
- one or more admin-managed public content entities
- publish/draft workflow
- slug-based public routes
- SEO basics

### Exclude for first pass

- full headless CMS
- page-builder complexity
- media library
- multilingual content management
- advanced SEO automation
- blog comments
- public checkout or commerce flows

## Recommended information architecture

### Public routes

- `/`
- `/about`
- `/features`
- `/contact`
- `/posts` or `/updates`
- `/pages/{slug}` for flexible public pages

### Private routes

- `/dashboard`
- `/admin/pages`
- `/admin/posts`
- `/admin/site-settings`

## Recommended first entities

### 1. `pages`

Use this for static or semi-static content.

Suggested fields:

- `title`
- `slug`
- `excerpt`
- `body`
- `status`
- `seo_title`
- `seo_description`
- `published_at`

### 2. `posts`

Use this for updates, announcements, or articles.

Suggested fields:

- `title`
- `slug`
- `summary`
- `body`
- `status`
- `published_at`
- `author_id`
- `seo_title`
- `seo_description`

### 3. `site_settings`

Use this for shared public-site content.

Suggested keys:

- site headline
- hero subtitle
- primary CTA label
- primary CTA URL
- footer text
- contact email
- social links

## Admin responsibilities

The admin backend should manage:

- page CRUD
- post CRUD
- publish/draft state
- ordering where needed
- SEO fields
- featured homepage content later if desired

## UI direction

The public website should feel intentional and premium, not like the admin app with the sidebar removed.

Recommended style goals:

- large hero
- strong typography
- structured content sections
- clear CTA hierarchy
- clean mobile behavior
- branded footer

## Permission model

New permissions should be added for public content administration, for example:

- `pages.view`
- `pages.create`
- `pages.update`
- `pages.delete`
- `posts.view`
- `posts.create`
- `posts.update`
- `posts.delete`
- `site-settings.view`
- `site-settings.update`

The public-facing pages themselves should remain accessible without authentication unless the project later adds gated content.

## Suggested implementation order

### Step 1

Create the public layout and landing page.

Deliverables:

- public header
- public footer
- hero
- feature sections
- CTA section

### Step 2

Add one admin-managed content model, preferably `pages`.

Deliverables:

- migration
- model
- factory
- admin CRUD
- publish/draft state
- public show page by slug

### Step 3

Add one repeatable content module, preferably `posts`.

Deliverables:

- index and detail pages on the public side
- admin CRUD
- publish filtering

### Step 4

Add site settings for reusable public content.

Deliverables:

- settings storage
- admin editing UI
- public layout integration

## Acceptance criteria

This phase is complete when:

- the project has a polished public landing page
- the public website uses its own layout system
- admin can manage at least one public content entity
- draft content is not visible publicly
- public pages have basic SEO fields
- the app can support both guest and admin experiences without UI confusion

## Long-term value

With this phase in place, future projects can start from:

- public website
- admin backend
- RBAC
- notifications
- API

instead of only from the private application layer.
