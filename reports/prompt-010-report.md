# Prompt 010 Report

Date: 2026-09-10
Status: IMPLEMENTED / CONDITIONAL RELEASE GATE

## Delivered

- Adult-caregiver email/password Signup and Login with normalized email, strong password rules, adaptive Laravel hashing, active-account enforcement, dedicated throttling and session rotation.
- Email verification, generic password-recovery response, reset-token flow, remember-token rotation and revocation of database sessions after Reset.
- Transactional and idempotent Guest-to-account continuity for owned Match and Play records with preflight conflict detection, consumed-token removal and append-only Audit evidence.
- Account Home with verification state, recent History, limited Saved capability, safe unavailable states and basic name/timezone Settings.
- Save/unsave only through actor-owned Play context; Saved covers are private, actor-scoped and fail closed unless Publication, Review, Facts and reviewed Cover are current.
- Optional post-completion account invitation and a persistent Header account action without blocking Guest Quick Match, Detail or Start.
- `OtpSender` contract bound to a disabled implementation; no OTP route, code creation, SMS send or phone verification was activated.
- Persian RTL Login, Signup, Verification, Recovery, Reset and Account surfaces using the existing Teelle Light/Dark tokens, natural marble image, responsive layout and reduced-motion contract.

## Security evidence

- Wrong credentials and disabled accounts share the same visible Login error.
- Known and unknown recovery emails share the same visible response.
- Login merge conflict removes the authenticated state, rotates the session again and leaves Guest ownership unchanged.
- Cross-user History is absent and cross-user Saved mutation/cover access is scoped before lookup.
- Verification uses Laravel signed routes; Logout invalidates the session and regenerates CSRF state.
- Password baseline rejects short, non-complex, common-root and highly repetitive values without adding a runtime third-party dependency.

## Verification

- Account lifecycle: PASS, 10 tests / 80 assertions.
- Result, Play and Saved authorization: PASS, 6 tests / 61 assertions.
- Full Laravel regression on isolated MySQL 26.7.0: PASS, 46 tests / 372 assertions; 8 destructive schema tests intentionally skipped in this aggregate run.
- MySQL schema/integrity excluding exact-version assertion: PASS, 7 tests / 36 assertions.
- JavaScript: PASS, 8 tests / 8.
- Production Vite build: PASS.
- Laravel Pint: PASS.
- Composer audit: PASS, no advisories.
- pnpm production audit: PASS, no known vulnerabilities.
- Physical browser: PASS for Desktop Login in Light/Dark, Signup, Verification, Account Home and narrow Mobile Account layout.

## Conditional release items

- Exact MySQL 8 runtime: NOT VERIFIED. The isolated local runtime is MySQL 26.7.0; Pars Pack's declared MySQL 8 service still needs a disposable credential and direct assertion.
- Production SMTP delivery: NOT VERIFIED. Mail code and fake-delivery tests pass, but Provider credentials, SPF, DKIM, DMARC and inbox delivery evidence are absent.
- Full breached-password corpus check: NOT ENABLED. The release currently uses a local strong/common/repetition baseline so Signup does not depend on a third-party network call.
- Active phone OTP: intentionally DISABLED pending a separately approved provider and prompt.

Prompt 011 remains locked until new explicit owner approval.
