# Threat Model

Status: ACCEPTED BASELINE
Phase: 11 - SECURITY & PRIVACY
Method: asset and trust-boundary review aligned with OWASP ASVS 5.0

## Protected assets

- حساب و Session بزرگسال
- Child Profile و Context بازی
- Game content، Safety rules و Publication state
- Play events و Heartbeat integrity
- Payment event و `JIGARI_ACTIVE` entitlement
- Admin roles، Audit log، secrets، Database و media assets

## Trust boundaries

Browser/PWA، Laravel application، MySQL 8 پارس‌پک، Queue/Scheduler، Object storage، Payment/Notification provider و Admin console مرزهای جدا هستند. هر داده عبوری از مرز خارجی untrusted است.

## Primary threats and controls

| Threat | Impact | Required controls |
| --- | --- | --- |
| Account takeover/session theft | دسترسی به داده خانواده | secure cookies، rotation، throttling، re-auth، MFA for Admin |
| Broken object authorization | مشاهده/تغییر داده دیگران | Policy on every resource، ownership scope، deny-by-default tests |
| SQL injection | data breach/corruption | Query Builder/Eloquent binding، allowlisted sort/filter، no raw user SQL |
| Stored/reflected XSS | session theft/admin compromise | Blade escaping، sanitization boundary، CSP، safe rich-text policy |
| CSRF | unauthorized mutation | Laravel CSRF، SameSite cookie، origin checks on sensitive callbacks |
| SSRF | internal/service access | no arbitrary URL fetch، allowlist provider endpoints، egress restriction |
| Malicious media/import | code execution/data poisoning | quarantine، signature/MIME/size checks، random names، outside webroot |
| Payment callback replay | free entitlement/fraud | signature، timestamp، unique provider event، idempotency and reconciliation |
| Play-event spam | false Heartbeat/analytics | actor/IP throttles، idempotency، session state validation، anomaly review |
| Unsafe game publication | child safety harm | separated edit/review/publish، content hash، audit، emergency unpublish |
| Secret leakage | full-system compromise | environment secret store، masking، rotation، no secret in Git/logs |
| Dependency/supply-chain compromise | client/server compromise | lockfiles، audit، trusted registries، reviewed upgrades، CSP/SRI where applicable |

## Highest-risk flows

Admin publication، role change، account recovery، guest-to-account merge، payment callback and file import require dedicated integration/E2E security tests before release.

## Residual risk

Legal interpretation، social engineering and Hosting control-plane security cannot be eliminated in application code. They require provider account MFA، limited staff access، incident procedure and periodic review.
