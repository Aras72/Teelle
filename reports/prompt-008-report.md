# Prompt 008 - Guest Quick Match Context

Date: 2026-09-09
Implementation: COMPLETE
Quality Gate: CONDITIONAL PASS
Next prompt: LOCKED until owner approval and ranking/content prerequisites

## Delivered

- Public Guest Quick Match flow from the Homepage CTA with one dominant Persian RTL question per screen.
- Age normalization for Persian, Arabic and Latin digits with the approved `6 <= age_months < 156` boundary.
- Context collection for situation, duration, location, materials and players/adult presence.
- Adaptive omission of the location question when `indoor-time` already implies `home-inside`.
- Server-side Back/Restart state, pseudonymous Guest identity, opaque Match ID, two-hour Match TTL and idempotent submission.
- Validation against active taxonomy, conflicting-material rejection, per-actor rate limiting and completed-session ownership checks.
- Teelle orbit motion with reduced-motion support and global light/dark continuity.

## Deliberate boundary

Energy and Mood are not asked while their Ranking weights remain unapproved. No recommendation or no-result evaluation is generated: submitted context remains in `Collecting` with zero `match_results`. This avoids presenting Draft/unreviewed games or silently relaxing Safety while the ranking contract is on Calibration Hold.

## Verification

- Guest Quick Match integration on disposable MySQL 26.7: PASS, 6 tests / 96 assertions.
- Full Laravel regression: PASS, 30 tests / 229 assertions; 8 strict MySQL tests intentionally skipped and executed separately.
- MySQL schema/integrity excluding exact-version assertion: PASS, 7 tests / 36 assertions.
- JavaScript tests: PASS, 8 tests. Vite production build: PASS.
- Pint: PASS. `pnpm audit`: PASS, no known vulnerabilities. `composer audit`: PASS, no security advisories.
- Physical-browser desktop review: PASS in light and dark themes; RTL, theme persistence, no horizontal overflow and no Console warning/error were verified at 1294x920.
- SQLite attempt: NOT RUN successfully because the installed PHP runtime has no SQLite driver; MySQL is the product database and all authoritative application tests above ran on MySQL.
- Exact MySQL 8 execution of the new migration: NOT VERIFIED; owner port 3500 credentials are not stored and that database was not touched.

## Gate and boundary

The Prompt 008 implementation is complete and its behavior is verified on MySQL, but the Gate remains CONDITIONAL until the same migration/test set runs on an exact MySQL 8 instance. Prompt 009 is not started by this delivery; it additionally requires explicit owner approval, published/reviewed game coverage and the frozen deterministic ranking contract.
