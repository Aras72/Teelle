# Authentication

Status: ACCEPTED SECURITY CONTRACT; PRIMARY LOGIN CHANNEL OPEN
Phase: 11 - SECURITY & PRIVACY

## Product boundary

- Guest MUST complete Quick Match، Result، Detail and Start without login.
- Account is for Saved/History and Jigari features؛ auth cannot obstruct first value.
- Primary identifier—email/password or mobile OTP—must be selected with the owner in PHASE 12 after provider/cost review. Neither is silently assumed here.

## First-party Web session

- Laravel server-side session with `Secure`، `HttpOnly` and `SameSite=Lax` cookie over HTTPS only
- Session ID regenerated after login، privilege change and re-authentication
- Server-side idle and absolute timeout؛ shorter Admin timeout
- Logout، password reset/recovery and suspected compromise revoke relevant sessions
- Authentication token/session ID MUST NOT be stored in `localStorage` or URL

## Credential requirements

- If password is selected: Laravel hashing with a modern adaptive algorithm، breached/common-password rejection، long password/passphrase support and no forced periodic reset without compromise.
- If OTP is selected: short expiry، one-time use، attempt limit، resend cooldown and protection against phone-number enumeration/SIM-swap risk.
- Recovery responses do not disclose whether an account exists.
- Sensitive changes require recent authentication.

## Admin

- Admin، Reviewer and Content Editor use separate privileged route protection.
- Admin and Publisher-capable roles require MFA before Production access.
- Role elevation، MFA reset and recovery are audited and invalidate prior privileged sessions.
- Shared Admin accounts are prohibited.

## Guest merge

Guest identity is random and signed. Merge occurs only after successful account authentication، validates ownership/idempotency and rotates the guest/session identifiers. Child PII is not stored in the guest cookie.

## Testing obligations

Enumeration، brute force، fixation، session reuse after logout، recovery abuse، privilege-change rotation and guest merge conflicts require automated tests.
