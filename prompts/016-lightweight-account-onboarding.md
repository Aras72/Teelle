# Prompt 016 — Lightweight Account Onboarding

Status: FROZEN / EXECUTED / PASS
Date: 2026-09-12
Phase: 13 execution prompt → Phase 14 implementation slice

## Objective

Give a newly verified adult account one short، skippable orientation before Account features while preserving the brief rule: value first، then signup، then optional subscription.

## Frozen scope

- Onboarding appears only for an authenticated، email-verified account that has not completed it.
- It explains free Guest play، Saved/History continuity and the minimum-child-data boundary.
- It asks for no child profile، purchase، marketing consent or repeated Match context.
- The adult can start a Match or skip directly to Account.
- Completion is server-side، one-time and idempotent; destinations are allowlisted.
- Existing authenticated Account/Jigari routes require completion، while public Match remains available.

## Acceptance evidence

- Authentication، verification، redirect، skip، idempotency and open-redirect protection are covered by tests.
- Desktop and 390×844 browser checks cover Light/Dark، RTL، overflow، touch targets and console.
- Full Laravel regression runs on MySQL 8.4.11 without skipped database contract tests.

## Excluded

Child Profile creation remains an explicit Jigari action. Ranking، content publication، Commerce and marketing consent are not added here.
