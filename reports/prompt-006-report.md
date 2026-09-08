# Prompt 006 - Content and Admin Foundation

Date: 2026-09-08
Implementation: COMPLETE
Quality Gate: CONDITIONAL PASS
Next prompt: LOCKED until owner approval and exact MySQL 8 migration evidence

## Delivered

- Frozen execution prompt and synchronized project status.
- Protected `/admin/content` shell with first-party roles/permissions and separate edit, review and publish abilities.
- Draft creation/editing, canonical content hash, review submission, independent approval/changes-requested, immutable approved versions and changed-content-only revision creation.
- Transactional publication with current approval/hash and required age, location, player, safety and reviewed cover checks. Immediate reasoned unpublish preserves history.
- Private randomized media quarantine with JPEG/PNG/WebP allowlist, signature/MIME agreement, 5MB and 400-4096px bounds, checksum deduplication, alt/crop metadata, independent review and authorized no-store preview.
- JSON import preview with zero Game mutation, atomic and idempotent confirmation, manifest ownership and draft-only idempotent rollback.
- Append-only mutation audit with actor, action, target, before/after, reason and request ULID.
- Responsive RTL Blade Admin UI using existing Teelle tokens and global themes, including text preview in light/dark. Authentication and MFA screens remain Prompt 010/out of scope.

## Verification

- Content/Admin MySQL integration: PASS, 7 tests / 42 assertions.
- Previous UI/foundation regression: PASS, 14 tests / 78 assertions.
- Existing MySQL behavior excluding version assertion: PASS, 6 tests / 10 assertions.
- Pint: PASS after formatting. Vite production build: PASS.
- `pnpm audit`: PASS, no known vulnerabilities. `composer audit`: PASS, no advisories.
- `git diff --check`: PASS.
- Authenticated Admin Blade screens are rendered by feature tests. Physical browser visual/keyboard audit: NOT RUN because login UI is intentionally deferred; this does not count as visual acceptance.

## Exact database evidence

- Owner-provided port 3500 is reachable, but the repository contains no credential and root without a password is correctly rejected. No operation was performed on that server.
- A disposable, isolated MySQL instance was initialized on port 15006 with a dedicated `_test` database and limited test user. Server version: 26.7.0.
- All new behavior and migration logic ran on that MySQL server. The pre-existing strict assertion requiring `VERSION()` to start with `8.` fails on 26.7.0, so exact MySQL 8 compatibility of this new migration is `NOT VERIFIED` in this run. Prior Prompt 002 evidence does not cover the new Prompt 006 migration.

## Gate and boundary

Prompt 006 is implemented and usable after authentication exists, but its gate remains CONDITIONAL until the same migration/test set runs against a disposable MySQL 8 database and an authenticated Admin browser audit is possible. Prompt 007 first game batch was not started.
