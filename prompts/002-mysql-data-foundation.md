# Prompt 002 - MySQL Data Foundation

Status: FROZEN

## OBJECTIVE

Implement the PHASE 10 MySQL 8 schema foundation، factories and deterministic system seeders.

## CONTEXT

Production database is MySQL 8 on Pars Pack. Minor/Patch must be recorded when a real connection exists.

## SOURCE OF TRUTH

`docs/10-data/*`، Product Rules/Invariants، Security and Prompt 001 output.

## SCOPE

Identity/guest، Game/version/taxonomy/material/safety/media، Review/Publication، Match/Play event، Heartbeat projection and operational tables in dependency-safe migration groups.

## OUT OF SCOPE

Production credentials، UI، real customer data، 250-game library، Matching algorithm، Payment provider and OTP activation.

## FILES TO READ

Prompt 001 report، `docs/10-data/*`، Product Rules/Invariants and Security checklist.

## FILES TO CREATE/MODIFY

Laravel migrations، models/enums needed for integrity، factories، seeders، database tests and synchronized status/report docs.

## REQUIREMENTS

- Laravel migrations are authoritative.
- MySQL-compatible JSON/index/constraint strategy only.
- Published version pointer، idempotency keys and append-only event contract are enforced.
- Seeders are idempotent and synthetic; demo games cannot appear Production Published.
- SQLite passing alone is not proof of MySQL compatibility.

## CONSTRAINTS

No production connection، no destructive reset of user data، no PostgreSQL-only feature and no content/UI implementation.

## EDGE CASES

Empty database، repeat migrate/seed، duplicate idempotency، invalid publication pointer، rollback/expand-contract and unsupported MySQL feature.

## TESTS

Migration from empty، seed twice، schema/integrity tests and MySQL 8 integration test when available. Unavailable real MySQL is `NOT RUN` and blocks this Prompt gate.

## QUALITY GATES

All migrations and MySQL integration checks pass with no destructive shortcut.

## DEFINITION OF DONE

Schema behavior matches Data docs، is committed/pushed and remote verified.

## EXPECTED REPORT

Tables، indexes، MySQL version evidence، tests and remaining risks.
