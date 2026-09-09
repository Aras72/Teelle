# Prompt 008 - Guest Quick Match Context

Status: FROZEN

## OBJECTIVE

Deliver the public, no-login Quick Match question flow and persist a minimal, validated, pseudonymous context snapshot without inventing ranking behavior.

## SOURCE OF TRUTH

The repository brief and approved Product, Rules, UX, Architecture, Data and Security contracts; especially `PR-QM-001` through `PR-QM-008`, DEC-007, DEC-008, DEC-009 and DEC-023.

## SCOPE

- Homepage CTA to a Persian RTL, one-question-per-screen Guest flow.
- Required age normalized to months, followed by situation and duration.
- Conditional hard-filter context for location, available materials and players/adult presence.
- Server-side session history with Back and Restart actions.
- Pseudonymous Guest identity, opaque public Match ID, TTL and idempotent submission.
- Persistent light/dark theme, Teelle motion signature and reduced-motion support.
- Validation, rate limiting, ownership checks and tests.

## ADAPTIVE CONTRACT

Age is the only unconditional product input. The current flow asks the minimum approved context needed by future hard filters. Selecting `indoor-time` infers `home-inside` and removes the redundant location question. Energy and Mood remain independent fields in the domain, but are not asked until approved ranking weights make them necessary. Noise and Mess remain conditional future inputs.

## OUT OF SCOPE

Ranking or recommendation, successful/no-result evaluation, public Result cards, Game Detail, Play events, Heartbeat mutation, account UI, OTP, publication of Draft games and Prompt 009 onward.

## SAFETY AND PRIVACY BOUNDARY

- No result is fabricated while the deterministic ranking contract is on Calibration Hold.
- Guest cookie/session data contains a random token only; its SHA-256 hash is stored server-side.
- Match access MUST remain bound to the same Guest identity or authenticated User.
- Age MUST satisfy `6 <= age_months < 156` and taxonomy inputs MUST be active canonical values.
- Retry MUST NOT create a second Match Session.

## TESTS AND QUALITY GATE

MySQL migration, valid and invalid age boundaries, Persian-digit normalization, ordered state transitions, adaptive question omission, material conflict, idempotent persistence, ownership recovery, full Laravel regression, JavaScript tests, production build, dependency audits, Pint and physical-browser review in both themes.

## DEFINITION OF DONE

The Guest context flow, migration, tests, documentation and report are committed/pushed and local HEAD equals `origin/main`. Prompt 009 remains locked until explicit owner approval and its ranking/content prerequisites are resolved.
