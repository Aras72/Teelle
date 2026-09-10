# Prompt 009 - Results, Detail, Play and Heartbeat

Date: 2026-09-10
Implementation: COMPLETE WITH CONDITIONAL PRODUCT GATE
Next prompt: LOCKED until explicit owner approval

## Delivered

- Completed Quick Match now redirects to an actor-scoped result-status URL.
- Honest `Collecting`, `NoResult` and integrity-failure states are available without fabricated recommendations.
- The success view renders exactly three persisted Published/Reviewed/complete results in the approved one-plus-two desktop layout and stacked mobile layout.
- Game Detail exposes duration, location, match explanation, required/optional materials, instructions and safety before Start.
- Reviewed Cover media is delivered through a private actor-scoped route with safe MIME allowlisting and `nosniff`.
- Play Start, Completion and optional one-time Rating are transactional, append-only and idempotent.
- Active Play includes the approved return guidance and CTA `بازی کردیم، برگشتیم`.
- A first valid Start increments the real `public_play_starts` projection once, so the Homepage display changes from the 110 baseline to 111; retries do not inflate it.
- Global theme continuity, RTL, responsive states, Teelle marble/orbit motion and reduced-motion handling are preserved.

## Fail-closed boundary

A successful result set is accepted only when it contains exactly three explained Match Results and every referenced immutable version is still the current active publication, has an approved latest review, complete facts and a reviewed Cover. Direct Detail, Cover and Start URLs enforce the same whole-set invariant. Removing any game from publication invalidates the full set and prevents Start.

## Deliberate product limitation

Prompt 009 does not add or simulate Ranking. The ranking contract remains on Calibration Hold and all 25 Pilot games remain Draft pending human content/safety/source review and reviewed Covers. Consequently the current real Quick Match flow lands on the honest `پیشنهادها هنوز آماده نیستند` state. Synthetic Published/Reviewed records exist only inside automated tests to verify the downstream lifecycle.

## Verification

- Prompt 009 MySQL integration: PASS, 5 tests / 57 assertions.
- Full Laravel regression on disposable MySQL 26.7: PASS, 35 tests / 288 assertions; 8 destructive schema tests intentionally skipped in this aggregate run.
- MySQL schema/integrity excluding exact-version assertion: PASS, 7 tests / 36 assertions.
- JavaScript tests: PASS, 8 tests. Vite production build: PASS.
- Pint: PASS.
- `pnpm audit --prod --audit-level high`: PASS, no known vulnerabilities.
- `composer audit --locked`: PASS, no security vulnerability advisories.
- Routes and changed PHP files: PASS syntax/registration checks.
- Exact MySQL 8 execution: NOT VERIFIED; the owner database on port 3500 was not touched because its project database/credentials were not provided.
- Physical-browser review of a real successful three-result path: NOT RUN because no production-valid Published/Reviewed set or Ranking output exists. Automated rendered-response coverage is PASS; it is not claimed as visual proof.

## Gate

The implementation slice is complete and regression-tested. The product gate is CONDITIONAL until Ranking weights are frozen, supported contexts have at least three Published/Reviewed games, and the exact MySQL 8/physical-browser checks are completed. Prompt 010 has not started.
