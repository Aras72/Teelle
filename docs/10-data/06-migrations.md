# Migration Strategy

Status: ACCEPTED BASELINE
Phase: 10 - DATA

## Principles

- Laravel migrations are the only authoritative Schema change path.
- Every migration is committed، reviewed، tested on empty and production-like databases and applied first in Staging.
- Production execution occurs through managed deploy automation، never as an owner Terminal task.
- Destructive change uses expand/migrate/contract across separate releases.

## Initial migration groups

1. Identity، guest and household
2. Game identity، versioning، taxonomy، materials، safety and media
3. Review، publication and audit
4. Match sessions/results and ruleset version
5. Play sessions/events and Heartbeat projection
6. Saved/history
7. Plans، purchases، payment events and entitlements
8. Outbox، jobs، cache and operational tables

## Seed strategy

- System taxonomy، roles، permissions and plan definitions use deterministic idempotent seeders.
- Demo/testing games are synthetic and never marked Production Published.
- Production game content enters through validated import/Admin workflow with Preview، human confirmation، audit and rollback manifest.
- A batch is not complete until image، copy، metadata، alt text، Safety and Coverage checks pass.

## Rollback

- Code rollback must remain compatible with the expanded Schema during rolling release.
- Data-destructive down migrations are not the primary Production rollback.
- Before risky migration، verified backup and tested recovery path are mandatory.

## Delivery schedule

- PHASE 10: logical model and Schema contract — complete.
- PHASE 12: migration tasks، test fixtures and Definition of Done.
- PHASE 14 foundation slice: create migrations، factories and system seeders.
- PHASE 14 content slice: Admin/import pipeline and first reviewed game batches.
- Before Beta: Golden Coverage Matrix and required Published library threshold pass.
