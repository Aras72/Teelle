# Prompt 014 — Admin Weekly Product Report and Export

Status: FROZEN / OWNER COMPLETION AUTHORIZATION / EXECUTED
Date: 2026-09-12
Phase: 13 execution prompt → Phase 14 implementation slice

## Objective

Deliver the independent Admin reporting slice required by `PRD-ADM-003` through `PRD-ADM-005`: a permission-protected weekly product report covering Funnel، Matching، Content، Search، Business and Technical health، with deterministic rule-based summary and downloadable PDF and CSV/Excel outputs.

## In scope

- An aggregate weekly report with an optional date range of at most 31 days.
- Funnel، Matching، Content، Search، Business and Technical sections.
- Fixed، deterministic health rules; no AI summary or generated recommendation.
- Privacy-preserving Search observations that retain counts only، never query text، user identifiers or child data.
- Server-side PDF and UTF-8 CSV compatible with Excel.
- Existing `analytics.view` permission، responsive RTL UI، persistent Light/Dark theme and Teelle motion identity.
- MySQL 8 migration and focused، regression، browser، PDF-render، build and dependency gates.

## Explicitly out of scope

- Product targets or alert thresholds not approved in source documents.
- User-level cohorts، raw queries، child-level analytics or export of personal data.
- Ranking calibration، Content publication، Commerce activation، Weekly Plan، Multi-child Matching and Production monitoring.

## Invariants

- Every export MUST be computed server-side from the same report source as the UI.
- A Matched session with a result count other than exactly three MUST be Critical.
- A critical Coverage gap or failed Outbox message MUST keep the summary fail-closed.
- Search analytics MUST NOT persist the query or actor.
- PDF generation MUST disable remote loading and PHP execution.
- A reversed or longer-than-31-day range MUST be rejected.

## Quality gate

Focused tests، full MySQL 8.4.11 regression، JavaScript، production build، Blade، Pint، Composer/pnpm audits، generated-PDF visual inspection and live Desktop/Mobile Light/Dark/Keyboard browser QA MUST pass.

## Delivery

Implementation، tests، decision، status، changelog، closure audit and report are committed and pushed; local `HEAD` equals `origin/main`. This Slice closes Admin report/export implementation but does not by itself complete Phase 14، pass Phase 15 or unlock Phase 16.
