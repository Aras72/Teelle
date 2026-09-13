# Abuse Prevention

Status: ACCEPTED BASELINE
Phase: 11 - SECURITY & PRIVACY

## Layered controls

- Rate limits combine route، actor/session and IP signals without treating IP as identity.
- Limits return a clear retry window and do not silently corrupt Match/Play state.
- Idempotency prevents duplicate Start، payment callback and import operations.
- Suspicious activity is logged with minimized data and reviewed through Admin tooling.

## Initial limits for Staging calibration

| Surface | Starting control |
| --- | --- |
| Login | 5 failed attempts per minute per identifier/IP with progressive cooldown |
| Recovery/OTP request | 3 per hour per identifier plus IP/device guard |
| Match create | 20 per 10 minutes per actor plus IP anomaly ceiling |
| Play event | 60 per minute per actor؛ one valid started per PlaySession؛ one new free Start per observed IP/Tehran day |
| Public Heartbeat | cached read endpoint؛ no write surface |
| Admin upload/import | low concurrency، size quotas and queue |
| Payment webhook | signature، timestamp window، provider ID uniqueness and rate limit |

Numbers are safe starting points، not production proof. Load tests and real abuse signals may adjust them through a documented Decision.

## Content and analytics integrity

- Match requires Published/Reviewed version and cannot be influenced by client score.
- Heartbeat projection counts only server-validated deduplicated `started` events.
- Rating spam is bounded to a valid PlaySession and one current rating event contract.
- Free Start quota is claimed transactionally only after Published/Reviewed and complete-result checks pass. Retry of the same PlaySession remains idempotent and an active Jigari entitlement bypasses the free quota.
- The quota stores only a date-scoped HMAC of the server-observed IP. Raw IP and a stable cross-day IP fingerprint are not persisted؛ forwarded headers remain untrusted unless the deployment explicitly configures the exact reverse proxy.
- Claimهای روزهای قبل با Scheduler روزانه پاک می‌شوند؛ فعال‌بودن Cron واقعی باید در Staging/Production جداگانه اثبات شود.
- Admin import validates every row and uses atomic or itemized rollback with audit.

## Response

Admin can disable account/session، revoke privileged access، unpublish unsafe content، quarantine a batch and inspect rate-limit/security events without Terminal. Automated permanent bans based only on IP are prohibited.
