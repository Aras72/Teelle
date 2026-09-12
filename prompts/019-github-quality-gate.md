# Prompt 019 — GitHub Quality Gate

Status: FROZEN / EXECUTED / PASS
Date: 2026-09-12
Phase: 15 testing and QA slice

## Objective

Run the repository quality baseline automatically for every pull request and every push to `main` so a local result cannot silently replace reproducible repository evidence.

## Frozen scope

- Use GitHub Actions with read-only repository contents permission and cancellation of stale runs.
- Run Laravel on PHP 8.5 against an isolated MySQL 8.4 service with schema validation enabled.
- Install locked Composer and pnpm dependencies.
- Run the complete PHPUnit suite، Pint، Blade compilation، JavaScript tests، production build and both dependency audits.
- Keep the owner workstation MySQL port `3500` out of automation.

## Acceptance evidence

- A repository test protects the workflow contract.
- The workflow file parses as YAML and the same quality commands pass locally.
- The first pushed GitHub Actions run is reported separately as `PASS`، `FAIL` or `UNVERIFIED`; creating the file alone is not a Remote pass.

## Excluded

This slice does not provide Pars Pack Staging، SMTP، licensed content، Ranking calibration، Legal approval، Production performance evidence or Launch authorization.
