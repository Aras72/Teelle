# Architecture Components

Status: PROPOSED
Phase: 09 - TECHNICAL ARCHITECTURE

## Web shell

- SSR layout، Theme bootstrap پیش از Paint، Navigation، SEO و PWA metadata
- Blade componentهای Design token-based
- Font self-hosting و Asset preload

## Experience layer

- Homepage Hero و Interactive Marble island
- Quick Match state flow
- Results و no-result
- Game Detail و Active Play
- Complete، Rating، Saved و History
- Jigari، Account و Settings

## Domain services

- `GameCatalog`: فقط Published/Reviewed و asset-complete
- `Eligibility`: Age، Safety، players، materials و location hard filters
- `MatchEngine`: scoring و diversity قطعی
- `ExplanationBuilder`: Copy مبتنی بر Metadata واقعی
- `PlaySession`: lifecycle و idempotent events
- `Heartbeat`: aggregate معتبر started
- `Entitlement`: دسترسی Jigari مستقل از Recommendation order

## Content pipeline

- Draft -> Review -> Published -> Archived
- تصویر، Crop، Alt text، Metadata و Safety note قبل از Publish اجباری‌اند.
- Preview دو Theme و Cropهای Responsive در Admin در نظر گرفته می‌شود.
- تغییر محتوا Audit می‌شود و نسخه قبلی قابل ردیابی باقی می‌ماند.

## Infrastructure adapters

- Database
- Cache
- Queue
- Filesystem and object storage
- Email and notification
- Payment gateways
- Analytics export

Adapterها نباید Ruleهای Domain را در خود پنهان کنند.
