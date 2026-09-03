# Integration Boundaries

Status: ACCEPTED BASELINE
Phase: 09 - TECHNICAL ARCHITECTURE

## Core rule

Quick Match، مشاهده بازی، Start و Safety به سرویس خارجی وابسته نیستند. شکست Integration نباید Core play flow را از کار بیندازد.

## Integration map

| Integration | Adapter boundary | Baseline | Failure behavior |
| --- | --- | --- | --- |
| PostgreSQL | Laravel database connection | Required | fail closed; health alert |
| Queue/Cache | Laravel contracts | Database driver first | synchronous safe fallback only where explicitly allowed |
| Game media | Laravel Filesystem | S3-compatible in Production | placeholder approved; never broken layout |
| Email | Notification channel | Provider pending | retry with backoff; no duplicate send |
| SMS/OTP | Notification/Auth adapter | Deferred until need confirmed | email/session auth remains independent |
| Payment | `PaymentGateway` interface | Provider pending | no entitlement before verified callback |
| Analytics | first-party event store/export | Required | queue/retry; no Core flow blocking |
| Error monitoring | PSR/Laravel reporting adapter | Provider pending | local structured log remains |
| 3D marble | bundled Three.js asset | Required enhancement | CSS/poster fallback |

## Payment and entitlement

- Gateway payload never directly activates `JIGARI_ACTIVE`.
- Callback signature، amount، currency، product، purchase identity و replay protection server-side بررسی می‌شوند.
- Purchase و provider event ذخیره می‌شوند؛ Entitlement از نتیجه verified ساخته می‌شود.
- Adapter ایرانی و Google Play آینده قرارداد مشترک دارند، اما TWA/Play integration تا Website Complete Gate قفل است.

## Content and media

- Admin فایل را به quarantine upload می‌کند؛ MIME، size، dimensions و malware policy پیش از publish بررسی می‌شوند.
- Media record به نسخه Asset، cropها، alt text، attribution و review state متصل است.
- URL عمومی از storage adapter تولید می‌شود و نام bucket/provider وارد Domain نمی‌شود.

## Reliability

- Outbound operationها timeout کوتاه، retry محدود با jitter و idempotency دارند.
- Dead-letter state در Admin قابل مشاهده و retry مجاز قابل اجراست؛ مالک به Terminal نیاز ندارد.
- Secretها فقط در Environment secret store/control panel هستند و هرگز وارد Repository یا Admin response نمی‌شوند.
- Provider change باید فقط Adapter و configuration را تغییر دهد، نه Ruleهای Domain.
