# Prompt 020 Report — Editable Provisional Jigari Pricing

Date: 2026-09-13
Result: IMPLEMENTED / PAYMENT DEFERRED POST-MVP

## Delivered

- Added provisional public prices of 390,000، 690,000 and 1,190,000 toman for the fixed 3/6/12-month Jigari plans.
- Added an Admin pricing surface protected by `subscription.manage`; title، toman price and public visibility are editable without Terminal access.
- Normalized Persian/Arabic digits and thousand separators، stored canonical values in IRR and rendered localized toman values publicly.
- Kept plan Code and Duration immutable and retained the no-trial/no-one-month contract.
- Added append-only Audit evidence for every accepted plan change.
- Kept Checkout، Purchase، Payment Event and entitlement activation absent.

## Verification

- Focused Plan pricing gate: 4 tests / 36 assertions، PASS on isolated MySQL 8.4.11.
- Combined Jigari pricing and entitlement regression: 11 tests / 92 assertions، PASS on isolated MySQL 8.4.11.
- Full Laravel regression: 99 tests / 841 assertions، PASS on isolated MySQL 8.4.11 with the database contract enabled.
- Focused MySQL foundation + Plan regression after the CI expectation correction: 12 tests / 78 assertions، PASS.
- PHP syntax and Laravel route discovery: PASS.
- JavaScript 8/8، Pint، Blade compilation، production Build، Composer audit and pnpm production audit: PASS.
- Browser QA for public prices and the authenticated Admin editor: PASS in Dark theme on Desktop and 390px Mobile؛ all three prices and the post-MVP gateway copy render with no console error.
- SQLite attempt: NOT RUNNABLE because the installed PHP runtime has no SQLite driver; no assertion executed and this is not counted as a pass.
- GitHub Actions Run `34723528510`: FAILED because the legacy foundation test still expected all seeded plans to be inactive. The assertion now requires the three DEC-040 provisional plans to be active; replacement Run `34743030855` on correction Commit `1886d5e` REMOTE PASS.

## Boundary

These prices are editable placeholders، not validated market pricing and not an offer backed by an active payment provider. Gateway integration، server-verified callback and paid entitlement activation are explicitly deferred until after MVP by owner direction.
