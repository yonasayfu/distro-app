# Decisions

This file records short architectural and workflow decisions so future implementation stays consistent.

## D-001: Boilerplate Identity Model

Decision:

- the boilerplate is `users`-centric

Reason:

- `users` is the most reusable identity model across Laravel applications
- `staff` is a downstream domain concept, not a universal starter requirement

Implication:

- V1 will build around `users`, `roles`, `permissions`, `notifications`, and `activity logs`

## D-002: Boilerplate Scope

Decision:

- V1 is domain-neutral and excludes asset-management modules

Reason:

- the goal is a reusable Laravel base, not a clone of the previous project

Implication:

- no assets, warranties, maintenance, procurement, audits, or domain-specific reports in V1

## D-003: Folder Structure

Decision:

- keep the Laravel 12 default project structure unless a clear need appears

Reason:

- Laravel conventions reduce friction
- future developers can navigate standard structure faster

Implication:

- no custom top-level architecture folders unless the value is clear and approved

## D-004: Documentation Workflow

Decision:

- implementation progress must update both task tracking and learning notes

Reason:

- the project is both a boilerplate and a Laravel learning path

Implication:

- update `BoilerplateTaskList.md` when work is completed
- append explanations to `laravelbasics.md` after meaningful implementation steps

## D-005: Git Workflow

Decision:

- use one branch per phase and commit at logical checkpoints

Reason:

- it keeps milestones clean and reviewable

Implication:

- each phase starts with a new branch
- each meaningful task batch should be committed and pushed
