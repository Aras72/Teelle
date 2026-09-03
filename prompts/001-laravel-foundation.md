# Prompt 001 - Laravel Foundation

Status: FROZEN

## OBJECTIVE

Create the executable Laravel Website foundation in `apps/web` without implementing product features.

## CONTEXT

Teelle is a Persian RTL responsive Website. PHP/Laravel is mandatory، Pars Pack/MySQL 8 is the production target and owner/user Terminal dependency is prohibited.

## SOURCE OF TRUTH

`AGENTS.md`، `PROJECT-STATUS.md`، `DECISIONS.md`، `docs/09-architecture/*`، `docs/11-security/*` and `docs/12-development/*`.

## SCOPE

- Laravel 13 application in `apps/web`
- PHP 8.5 target and Composer lockfile
- Blade SSR، Livewire 4 patched release and Vite
- test framework selection and scripts
- Persian locale/timezone baseline، RTL document shell and health endpoint
- safe `.env.example` with no credential

## OUT OF SCOPE

Database domain migrations، final UI، Homepage، marble، Auth screens، Matching، Admin، Payment and TWA.

## FILES TO READ

Architecture، Security checklist، Coding standards and Git strategy.

## FILES TO CREATE/MODIFY

Only `apps/web/**` plus root documentation/status needed to report the slice.

## REQUIREMENTS

- Use official Laravel packages and locked versions.
- Do not copy a generic starter visual into approved Teelle UI.
- Application boots without requiring owner actions.
- `/up` or equivalent health route responds without exposing secrets.
- Default page identifies Teelle minimally and remains temporary.

## CONSTRAINTS

No runtime AI، no mobile app، no external auth provider، no secret and no product-rule implementation.

## EDGE CASES

Missing local PHP/Composer/Node must be reported honestly. A downloaded toolchain may be used only as a development dependency and not committed.

## TESTS

Dependency install، framework version checks، PHP tests، production asset build and secret/diff checks.

## QUALITY GATES

All executed commands exit zero؛ `composer.lock` and frontend lockfile exist؛ app and assets build reproducibly.

## DEFINITION OF DONE

Foundation is committed/pushed، remote HEAD matches and no later Prompt scope is implemented.

## EXPECTED REPORT

Versions، paths، tests with PASS/FAIL/NOT RUN، commit and remote verification.
