# Prompt 026 — Phase 14/15 owner inputs and release-candidate preparation

Date: 2026-09-14
Status: REPOSITORY WORK PASS / OWNER CONTENT، RETENTION AND PRODUCTION EVIDENCE OPEN

## Delivered

- Created `docs/14-operations/templates/Teelle_Game_Review_Template_v1.xlsx` from the canonical 25-game pilot source. The workbook contains the complete game metadata، yellow owner-review columns، image fields and 12 ranking scenarios.
- Documented `/admin/content`، single-game Draft creation، safe Pilot import، five-domain review and audited publication.
- Added a discoverable Admin navigation link for staff and a protected `/admin/content/accounts` page with `users.manage` permission and an audited three-day reactivation button.
- Finalized the existing Hero video for Website MVP under owner decision DEC-044.
- Added a public Persian Privacy Policy draft، required registration acceptance and versioned acceptance evidence on the user record.
- Added a three-day `deletion_pending` transition with session revocation. Hidden indefinite personal-data retention was not implemented. Post-grace processing remains disabled until the owner chooses a transparent deletion or deactivation model.
- Switched CI from PHP 8.5 to the owner-confirmed Pars Pack PHP 8.4 target and added a no-terminal deployment Artifact containing Vendor and production assets but no `.env` or secrets.
- Added a production environment template and a Persian manual Pars Pack transfer runbook for `teelle.ir`، MySQL 8، SSL، SMTP، Cron، backup and rollback verification.

## Verification

- Workbook: four rendered previews inspected؛ three key ranges inspected؛ formula-error scan returned zero matches.
- PHP syntax: changed controllers، routes and migration PASS.
- Focused MySQL integration: 16 tests / 136 assertions PASS.
- CI contract + Jigari query-budget regression: 7 tests / 60 assertions PASS.
- Full local regression on isolated MySQL-compatible 26.7 runtime: 101 PASS / 3 FAIL before fixes؛ the CI contract and query-budget failures were fixed and passed on rerun. The remaining version assertion correctly rejects local `26.7.0` because the production contract requires MySQL 8. Remote CI on `mysql:8.4` is the authoritative final database-version gate.
- JavaScript: 8/8 PASS.
- Vite production build: 58 modules PASS.
- Pint، Blade compile، Composer audit and pnpm production audit: PASS، no known vulnerabilities.

## Phase boundary

Phase 14 remains open only for returned owner review/priority choices، licensed covers، independent human review/publication and calibrated real Result/Play E2E. Phase 15 repository preparation is complete، but Production PASS still requires manual hosting transfer، MySQL import rehearsal، SSL/domain validation، SMTP and DNS delivery evidence، real accessibility/performance checks، backup/rollback and the final transparent retention choice. Phase 16 cannot truthfully be closed before those Production checks.
