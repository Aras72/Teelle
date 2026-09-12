# Prompt 017 Report — Editorial Collections

Date: 2026-09-12
Result: PASS

## Delivered

- Added `editorial_collections` and ordered `editorial_collection_game` schema with explicit draft/published lifecycle and accountable actors.
- Added protected Admin create/edit/publish/unpublish workflows with separate edit and publish permissions and append-only audit events.
- Added public Collection index، detail and reviewed-cover routes.
- Reused the complete Published/Reviewed candidate contract so stale، unsafe، incomplete or unreviewed games fail closed at publication and read time.
- Added honest empty states، SEO-facing slugs، the global Collections navigation link and photorealistic marble visuals.

## Verification

- Focused Collections + MySQL schema gate: 12 tests / 67 assertions، PASS.
- Order-dependent Content Admin + Collections regression: 14 tests / 80 assertions، PASS.
- Full Laravel regression on isolated MySQL 8.4.11: 92 tests / 764 assertions، zero failures and no skipped database contract tests، PASS.
- JavaScript: 8/8 PASS؛ Production build، Blade compilation and Pint: PASS.
- Composer and pnpm audits: no known vulnerabilities.
- Browser: public and Admin surfaces at 1440، 652 and 390، Light/Dark، RTL، no horizontal overflow، no visible control below the project touch-target tolerance and no Console warning/error.

## Defect found and closed

The full ordered suite exposed a joined-column collision where the pivot table `id` could overwrite `games.id`. Selecting `games.*` explicitly fixed the production query; the order-dependent regression now passes.

## Boundary

This closes FEAT-021 implementation. It does not publish the 25 Draft pilot games، calibrate Ranking، create Weekly Plan/Multi-child flows، enable Commerce or pass Phase 15 Production QA.
