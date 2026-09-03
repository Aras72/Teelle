# API Design

Status: ACCEPTED BASELINE
Phase: 09 - TECHNICAL ARCHITECTURE

## Shape

- Browser-first Website از Blade SSR و Livewire استفاده می‌کند؛ REST جای Rendering اصلی را نمی‌گیرد.
- Contractهای مستقل زیر `/api/v1` قرار می‌گیرند تا PWA، Automation مجاز و TWA آینده Backend مشترک داشته باشند.
- پاسخ‌ها JSON UTF-8، زمان‌ها UTC و شناسه‌ها opaque هستند.
- Breaking change فقط با نسخه API جدید مجاز است.

## Resource groups

| Area | Baseline contract | Access |
| --- | --- | --- |
| Home | `GET /api/v1/public/heartbeat` | Public، cached aggregate |
| Taxonomy | `GET /api/v1/taxonomy` | Public read-only |
| Matching | `POST /api/v1/matches`، `GET /api/v1/matches/{id}` | Guest or member |
| Games | `GET /api/v1/games/{slug}` | Published content only |
| Play | `POST /api/v1/play-sessions`، `POST /api/v1/play-sessions/{id}/events` | Guest or member |
| Saved/history | `/api/v1/me/saved-games`، `/api/v1/me/play-history` | Authenticated; entitlement rules apply |
| Account | `/api/v1/me`، child profiles and preferences | Authenticated |
| Admin | `/api/v1/admin/*` | Admin policy plus audit |

## Matching request contract

ورودی ساختاریافته شامل `age_months` و فقط Contextهای پاسخ‌داده‌شده است: situation، duration، location/space، materials، players/adult presence، energy، mood، noise و mess. API متن آزاد را به‌عنوان دستور Matching اجرا نمی‌کند.

خروجی موفق دقیقاً سه `game_id` یکتا همراه explanation ساخته‌شده از Rule/Metadata دارد. کمتر از سه Survivor با `no_result`، reason code و در صورت امکان safe-adjustment قابل انتخاب برمی‌گردد. Safety و Age هرگز قابل Relax نیستند.

## Event and idempotency contract

- `started`، `completed` و `rated` Eventهای append-only با timestamp سرور هستند.
- هر mutation حساس Header یا field پایدار `idempotency_key` دارد؛ retry همان نتیجه را برمی‌گرداند و Event دوم نمی‌سازد.
- `completed` بدون `started` و `rated` بدون Play Session معتبر رد می‌شود.
- Heartbeat فقط `started` معتبر و deduplicated را aggregate می‌کند.
- Offline queue با همان کلید idempotency reconcile می‌شود.

## Authentication and errors

- First-party Web از secure، HttpOnly، SameSite cookie و CSRF protection استفاده می‌کند.
- Guest با شناسه تصادفی امضاشده شناخته می‌شود و PII کودک در cookie ذخیره نمی‌شود.
- API خارجی آینده از token کوتاه‌عمر و scope محدود استفاده می‌کند؛ refresh credential در storage امن نگهداری می‌شود.
- Validation errorها field-level هستند. خطاهای Domain code پایدار دارند و stack trace یا secret برنمی‌گردانند.
- Rate limit برای Auth، Match، Event، Search و Admin جدا تعریف می‌شود.

## Evolution and observability

- Laravel Form Request و Resource/DTO مرز Contract هستند؛ Model مستقیماً serialize نمی‌شود.
- هر درخواست `request_id` دارد؛ user identifier در log hash یا pseudonymous است.
- API schema و exampleها پیش از Implementation هر Slice تست contract خواهند داشت.
