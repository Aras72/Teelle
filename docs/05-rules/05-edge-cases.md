# Edge Cases — تیله

Status: IN_REVIEW
Phase: 05 — PRODUCT RULES

## Age

- Exactly 6 months: eligible.
- One day before completing 6 months: not eligible.
- Exactly 12 years: eligible.
- One day before 13th birthday: eligible.
- On/after 13th birthday: not eligible.
- Guest enters ambiguous «نیم سال»: normalize only through explicit UI selection.
- Birth date missing or invalid: no Match until valid age exists.
- Timezone changes around birthday: server/product timezone rule must be consistent.

## Matching

- Zero, one or two Survivors: explicit no-result, never pad with invalid games.
- Three Survivors from one Core Game: diversity rule prevents duplicate Variations.
- Tie score: deterministic stable tie-breaker.
- Game becomes Unpublished between Result and Detail: explain unavailability and offer re-match.
- Required material answer unknown: do not assume availability.
- Safety restriction conflicts with preference: Safety wins.
- Location or space missing: request only the minimum required clarification.
- Multi-child ages have no common safe game: explicit no-result and safe alternative Context guidance.

## Play events

- Double click/retry on Start: one valid started event.
- Offline Start: queue/reconcile without double count; behavior requires architecture contract.
- User never returns: Session remains Started then may become Abandoned by explicit timeout rule.
- Rating submitted twice: update semantics must preserve event audit or reject duplicate.
- Guest clears storage: local history may be lost and must be disclosed.

## Membership

- Payment succeeds but callback is delayed: no client-only activation; show pending.
- Duplicate callback: idempotent entitlement update.
- Expiry during active Match/Session: current free core continues; premium navigation updates safely.
- Refund after use: revoke future premium access without rewriting past play events.
- Plan changes price while checkout open: server price is authoritative and change is disclosed.

## Content/Admin

- Import duplicate slug/source: detect before commit.
- Reviewer edits content after approval: approval invalidates.
- Emergency Safety issue: immediate Unpublish with mandatory reason and audit.
- Coverage dashboard uses stale index: show freshness timestamp and fail closed for release gate.
- CSV/Excel export contains formula-leading text: neutralize spreadsheet formula injection.

## Motion and interaction

- Device has no Pointer/hover: Touch drag works; experience remains understandable without hover.
- `prefers-reduced-motion`: remove continuous/large motion while preserving identity and function.
- WebGL/advanced rendering unavailable: static or CSS fallback preserves Hero and CTA.
- User drags outside Marble bounds: pointer capture/release must not trap input.
- Keyboard user: focus and rotation alternative are available without blocking CTA.
- Low-power device: quality degrades before frame rate or input responsiveness.
- Tab backgrounded: animation pauses.

## RTL and localization

- Persian digits versus Latin digits normalize safely.
- Mixed RTL/LTR game names and units render correctly.
- Relationship name absent or unsafe: generic respectful copy is used.
- Long translated Safety text does not hide Start requirements.
