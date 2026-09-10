# Prompt 009 - Results, Detail, Play and Heartbeat

Status: FROZEN

## OBJECTIVE

Deliver the safe public experience after Quick Match submission: honest result states, a complete three-game result set when valid persisted recommendations exist, Game Detail, Play start/completion, optional rating and real Heartbeat mutation.

## SOURCE OF TRUTH

The repository brief and approved Product, Ranking, Safety, State Machine, UX, Architecture, Data and Security contracts; especially DEC-012, DEC-014, DEC-015, DEC-019, DEC-023 and DEC-024.

## SCOPE

- Redirect a completed Quick Match submission to its actor-scoped result status.
- Render explicit `Collecting`, `NoResult` and integrity-failure states without fabricated recommendations.
- Render successful Results only for exactly three persisted, explained Match Results whose immutable versions are currently Published, independently approved, structurally complete and backed by a reviewed Cover.
- Serve private reviewed Cover media through actor-scoped routes with MIME allowlisting and `nosniff`.
- Render Game Detail with duration, locations, explanation, materials, instructions and safety information before Start.
- Start one idempotent Play Session per Match Result and append exactly one `started` event.
- Increment `public_play_starts` only when that valid event is first recorded; Homepage remains `110 + started_count` per DEC-019.
- Provide the quiet Active Play screen, return guidance and CTA `بازی کردیم، برگشتیم`.
- Complete Play idempotently and accept at most one optional rating after completion.
- Preserve global light/dark theme, RTL, responsive Results layout, Teelle motion identity and reduced-motion behavior.

## CONDITIONAL DELIVERY BOUNDARY

The owner explicitly approved starting Prompt 009 while Ranking remains on Calibration Hold and the Pilot library remains Draft. Therefore this prompt delivers and verifies the downstream Result/Detail/Play foundation, but MUST NOT invent the missing matching algorithm or publish Draft content. The real Quick Match path remains honestly in `Collecting` until deterministic weights and sufficient Published/Reviewed coverage are approved. Successful-result tests use synthetic test-only records and are not production content.

## SAFETY, INTEGRITY AND PRIVACY

- A direct URL MUST NOT bypass actor ownership, publication state or the complete three-result invariant.
- If any one of the three results becomes unavailable, the whole set fails closed and no Play may start.
- Start availability is rechecked inside the transaction immediately before the event is written.
- Completion before Start and rating before Completion MUST fail.
- Start, Complete and Rate events are append-only and idempotent; retries MUST NOT inflate Heartbeat or replace a rating.
- Public IDs are opaque; Guest access remains bound to the unexpired pseudonymous identity in the session.

## OUT OF SCOPE

Ranking weight selection, candidate scoring, automatic `Matched`/`NoResult` evaluation, publication or review of Pilot games, account/authentication UI, guest-to-account continuity, SMS/OTP, commerce and Prompt 010 onward.

## TESTS AND QUALITY GATE

MySQL feature tests MUST cover actor scoping, honest states, exactly-three rendering, reviewed Cover delivery, incomplete/unpublished fail-closed behavior, Start/Complete/Rate state rules, idempotency and Heartbeat. The full Laravel regression, schema/integrity tests excluding an unavailable exact-version assertion, JavaScript tests, production build, Pint and dependency audits MUST pass. Exact MySQL 8 and physical-browser review of a real successful recommendation remain separately reportable and MUST NOT be claimed when unavailable.

## DEFINITION OF DONE

Implementation, tests, decision record, status, changelog and report are committed and pushed; local HEAD equals `origin/main`. Prompt 010 remains locked until a new explicit owner approval.
