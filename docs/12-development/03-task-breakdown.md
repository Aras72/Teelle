# Task Breakdown

Status: FROZEN BASELINE
Phase: 12 - DEVELOPMENT PLANNING

## Stage 0

- `DEV-001` scaffold Laravel 13/Livewire 4 application and lock dependencies
- `DEV-002` configure Vite، RTL font/assets and quality scripts
- `DEV-003` configure MySQL environment contract and CI test database
- `DEV-004` add health، logging، error handling and secret-safe config

## Stage 1

- `DATA-001` identity/guest/household migrations and factories
- `DATA-002` game/version/taxonomy/material/safety/media migrations
- `DATA-003` review/publication/audit migrations
- `DATA-004` match/result/play/event/projection migrations
- `DATA-005` commerce/outbox/job/cache tables
- `DATA-006` deterministic system seeders and migration integrity suite

## Stage 2

- `UI-001` implement tokens، typography، RTL shell and global theme persistence
- `UI-002` implement responsive Homepage v4 static structure and content
- `UI-003` build marble rendering spike and measure weak-device budget
- `UI-004` implement pointer، click، drag، touch، keyboard and reduced-motion states
- `UI-005` add CSS/poster fallback and browser/accessibility tests

## Stage 3

- `CONTENT-001` implement staff roles/Policies and audited Admin shell
- `CONTENT-002` implement Game draft/version editor
- `CONTENT-003` implement secure media upload and approved crop package
- `CONTENT-004` implement review/publish/unpublish lifecycle
- `CONTENT-005` implement atomic import preview/confirm/rollback
- `CONTENT-006` load and verify initial game batches against Coverage matrix

## Stage 4

- `CORE-001` adaptive Context form with age as only fixed question
- `CORE-002` deterministic matching filters/scoring/diversity with golden tests
- `CORE-003` Results/no-result and explanation contract
- `CORE-004` Game Detail and Safety-before-Start
- `CORE-005` idempotent Start، offline reconciliation and Heartbeat projection
- `CORE-006` Active Play، return CTA، complete and rate flow

## Stage 5+

- `AUTH-001` email/password registration/login/session
- `AUTH-002` SMTP verification/reset and delivery tests
- `AUTH-003` guest merge، Saved/History and authorization tests
- `AUTH-004` optional disabled phone/OTP adapter contract
- `JIG-001..006` entitlement، profiles، multi-child، plan/search and payment
- `OPS-001..006` reports، privacy، security، performance، backup restore and launch

Every task becomes an execution prompt in PHASE 13 and stops at its own Quality Gate.
