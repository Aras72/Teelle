# Prompt 015 — PWA Readiness

Status: FROZEN / EXECUTED / CONDITIONAL PASS
Date: 2026-09-12
Phase: 13 execution prompt → Phase 14 implementation slice

## Objective

Make the existing responsive Laravel website installable-capable and resilient to navigation loss without starting TWA or creating a parallel frontend.

## Frozen scope

- A Persian RTL Web App Manifest with standalone display، stable scope/start URL and real approved Teelle marble icons.
- Service Worker registration from the existing web shell.
- A self-contained Light/Dark offline recovery page.
- A visible، keyboard-operable update notice when a waiting worker is ready.
- Static-asset caching only. Navigation responses، API calls، non-GET requests and user-specific HTML MUST NOT be persisted.
- Old cache cleanup and an explicit update activation path.
- Apache delivery metadata for the Manifest MIME type and a no-cache Service Worker script.

## Security and privacy boundary

The worker MUST remain same-origin and fail open for normal website use. It MUST NOT queue writes، cache account/admin/match/result/play pages، inspect payloads or store personalized HTML. Offline Play reconciliation remains outside this slice.

## Acceptance evidence

- Focused feature tests cover metadata، manifest، assets، offline recovery and the no-navigation-cache contract.
- Service Worker syntax، manifest JSON، production build، Blade، Pint، JavaScript and dependency audits pass.
- Desktop browser verifies manifest linkage، RTL page rendering، no overflow and a 44px recovery action.
- Real registration/install/update/offline simulation remains `NOT VERIFIED` where the controlled browser does not expose Service Worker APIs; Production HTTPS installability remains a Phase 15 gate.

## Closure boundary

This slice closes local PWA implementation readiness only. It does not pass Phase 15، start TWA or establish production installability.
