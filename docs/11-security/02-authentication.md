# Authentication

Status: ACCEPTED BASELINE
Phase: 11 - SECURITY & PRIVACY

## Product boundary

- Guest MUST complete Quick Match، Result، Detail and Start without login.
- Account is for Saved/History and Jigari features؛ auth cannot obstruct first value.
- Email and password is the active MVP login method.
- Mobile number and OTP is an optional second login method behind a disabled feature flag until an SMS provider، cost and delivery reliability are approved.

## First-party Web session

- Laravel server-side session with `Secure`، `HttpOnly` and `SameSite=Lax` cookie over HTTPS only
- Session ID regenerated after login، privilege change and re-authentication
- Server-side idle and absolute timeout؛ shorter Admin timeout
- Logout، password reset/recovery and suspected compromise revoke relevant sessions
- Authentication token/session ID MUST NOT be stored in `localStorage` or URL

## Email and password baseline

- Laravel built-in authentication، email verification and password reset are used through the Livewire starter baseline.
- Password uses Laravel hashing with a modern adaptive algorithm، breached/common-password rejection، long password/passphrase support and no forced periodic reset without compromise.
- Login itself does not require an external identity service. Verification and password-reset delivery require configured SMTP/email transport؛ Pars Pack mail capability or an external provider must be verified before Production.
- Recovery responses do not disclose whether an account exists.
- Sensitive changes require recent authentication.

## Optional mobile OTP

- OTP delivery requires an external SMS gateway/provider and remains disabled until one is selected.
- Phone numbers are normalized to E.164، unique only after verification and attached to the existing `user_id`.
- OTP has short expiry، one-time use، hashed storage، attempt limit، resend cooldown and SIM-swap/enumeration protections.
- Adding or replacing a phone requires an authenticated/re-authenticated session plus OTP verification.
- If verified email and phone resolve to different accounts، automatic merge is prohibited؛ recovery/support workflow must resolve ownership.

## Admin

- Admin، Reviewer and Content Editor use separate privileged route protection.
- Admin and Publisher-capable roles require MFA before Production access.
- Role elevation، MFA reset and recovery are audited and invalidate prior privileged sessions.
- Shared Admin accounts are prohibited.

## Guest merge

Guest identity is random and signed. Merge occurs only after successful account authentication، validates ownership/idempotency and rotates the guest/session identifiers. Child PII is not stored in the guest cookie.

## Testing obligations

Enumeration، brute force، fixation، session reuse after logout، email verification/reset، recovery abuse، privilege-change rotation، optional OTP and guest merge conflicts require automated tests.
