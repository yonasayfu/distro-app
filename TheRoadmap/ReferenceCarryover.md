# Reference Carryover

This file records what was intentionally copied forward from `Refernce/` into `TheRoadmap/`.

The goal is simple:

- preserve reusable information
- avoid dragging project-specific noise into the boilerplate
- make it easier to delete `Refernce/` later

## Reusable Information Preserved

The following reference themes have been carried into `TheRoadmap/`:

- Boilerplate planning and graduation criteria
- UI shell and CRUD consistency patterns
- RBAC and role-matrix direction
- Notifications and email setup direction
- Activity log and audit-log direction
- API readiness and versioning direction
- Operations and setup concerns
- Developer-experience concerns such as CI and onboarding

## Main Reference Files Represented Here

- `BoilerplateFeaturesPlan.md`
- `BoilerplateFeaturesTasks.md`
- `FeatureImplementationGuide.md`
- `GerayeFeatureMigrationPlan.md`
- `GerayeFeatureMigrationTasks.md`
- `thesecondBase.md`
- `ApiIntegrationGuide.md`
- `EmailImplementationGuide.md`
- `RBAC_User_Guide.md`
- `OPERATIONS.md`

## Reference Material Intentionally Not Carried as Boilerplate Scope

These files were useful for understanding the previous app, but they should not define the new base starter:

- `MyNewProjectRoadmap.md`
- `MyNewDatabaseSchema.md`
- `ROADMAP.md`
- `TASKS.md`
- `TASK_CHECKLIST.md`
- `upgradetofreshservice.md`
- Freshservice phase notes
- Asset-specific reports, audit, import, and maintenance docs
- Company, staff, customer, and asset-specific workflow notes

## Why They Were Left Out

They describe a downstream product, not a neutral reusable starter.

A real boilerplate should give future projects:

- identity and access control
- shell and design conventions
- cross-cutting infrastructure
- reusable CRUD patterns
- setup and operational readiness

It should not force every future project to inherit an asset-management schema.

## Safe Deletion Guidance

After you confirm that `TheRoadmap/` contains enough planning detail for implementation, you can manually delete `Refernce/`.

Before deleting it, verify that you are comfortable with:

- `BoilerplateRoadmap.md`
- `BoilerplateTaskList.md`
- `followTemplate.md`

Those three files are the main long-term replacements for the reusable parts of the reference folder.
