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
| Play event | 60 per minute per actor؛ one valid started per PlaySession |
| Public Heartbeat | cached read endpoint؛ no write surface |
| Admin upload/import | low concurrency، size quotas and queue |
| Payment webhook | signature، timestamp window، provider ID uniqueness and rate limit |

Numbers are safe starting points، not production proof. Load tests and real abuse signals may adjust them through a documented Decision.

## Content and analytics integrity

- Match requires Published/Reviewed version and cannot be influenced by client score.
- Heartbeat projection counts only server-validated deduplicated `started` events.
- Rating spam is bounded to a valid PlaySession and one current rating event contract.
- Admin import validates every row and uses atomic or itemized rollback with audit.

## Response

Admin can disable account/session، revoke privileged access، unpublish unsafe content، quarantine a batch and inspect rate-limit/security events without Terminal. Automated permanent bans based only on IP are prohibited.
