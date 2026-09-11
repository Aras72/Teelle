# Security Checklist

Status: DESIGN REVIEW PASS; EXECUTION NOT RUN
Phase: 11 - SECURITY & PRIVACY
Verification target: OWASP ASVS 5.0 risk-based baseline

## Before implementation

- [x] Threat assets and trust boundaries documented
- [x] Guest flow separated from authenticated account
- [x] Server-side authorization and role separation documented
- [x] Injection، XSS، CSRF and SSRF controls specified
- [x] File upload quarantine and validation specified
- [x] Payment replay/idempotency specified
- [x] Child data minimization and privacy controls specified
- [x] Secrets، logging، backup and recovery boundaries specified
- [x] Email/password approved as MVP primary login؛ optional mobile OTP remains provider-gated
- [ ] Legal duration for payment/accounting retention confirmed
- [ ] Exact Pars Pack plan capabilities verified

## Implementation acceptance

- [ ] Dependency audit and lockfile review
- [ ] Unit/Feature tests for Policies، validation and state transitions
- [ ] Negative authorization and IDOR tests
- [ ] Session fixation، logout/recovery and brute-force tests
- [ ] Stored/reflected XSS and CSRF tests
- [ ] SQL injection and unsafe sort/filter tests
- [ ] Upload polyglot، double-extension، oversized image and archive-bomb tests
- [ ] Webhook signature/replay and entitlement tests
- [ ] Security headers، TLS and CSP verification in Staging
- [ ] Secret scan and production debug-off verification
- [x] Local isolated MySQL 8.4.11 restore drill with schema/migration parity
- [ ] Staging encrypted/off-site backup restore and access review
- [ ] Privacy export/deletion workflow tests

Unchecked items are `NOT RUN` or `OPEN` and MUST NOT be represented as passed. Architecture Gate remains pending through PHASE 12.

## Sources

- https://owasp.org/www-project-application-security-verification-standard/
- https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html
- https://cheatsheetseries.owasp.org/cheatsheets/Session_Management_Cheat_Sheet.html
- https://cheatsheetseries.owasp.org/cheatsheets/File_Upload_Cheat_Sheet.html
