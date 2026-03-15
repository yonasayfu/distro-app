# TheRoadmap

This folder is the standalone planning set for turning this repo into a reusable Laravel 12 + Inertia + Vue boilerplate.

It exists so the important planning and UI guidance can live outside `Refernce/` and remain safe even after you manually delete that folder.

## Files

- `BoilerplateRoadmap.md`: the full implementation roadmap, scope, and phase-by-phase delivery plan.
- `BoilerplateTaskList.md`: the detailed checklist version of the roadmap.
- `followTemplate.md`: the extracted UI, UX, and implementation patterns to follow later during code work.
- `ReferenceCarryover.md`: what was intentionally preserved from `Refernce/` and what was intentionally left out.
- `decisions.md`: short architectural and workflow decisions for the boilerplate.
- `laravelbasics.md`: learning notes that explain what was implemented and which Laravel concepts were involved.
- `mcpguidance.md`: practical guidance for Laravel MCP, Herd MCP, and how they help during development.
- `gitguidance.md`: the branch, commit, phase, and push workflow for this project.

## Current Direction

The target is a domain-neutral boilerplate, not an asset-management clone.

That means the reusable V1 scope is:

- Auth, security, profile, appearance
- RBAC and policy foundation
- Admin shell and reusable CRUD patterns
- Users and roles administration
- Notifications and activity logs
- API baseline
- Developer and operations docs

## Working Rule

From now on:

- completed work should be reflected in `BoilerplateTaskList.md`
- Laravel learning notes should be appended to `laravelbasics.md`
- phase and branch workflow should follow `gitguidance.md`
- MCP usage notes and practical guidance should live in `mcpguidance.md`
