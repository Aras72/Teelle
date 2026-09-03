# Environments

Status: ACCEPTED BASELINE
Phase: 09 - TECHNICAL ARCHITECTURE

## Environment matrix

| Environment | Purpose | Data | Access | Operation path |
| --- | --- | --- | --- | --- |
| LOCAL | Development and automated tests | synthetic only | developers | scripted setup allowed |
| DEVELOPMENT | shared integration and previews | synthetic/anonymized | team | CI and managed dashboard |
| STAGING | release candidate and acceptance | production-like, no raw production PII | restricted team/owner review | deployment automation + control panel |
| PRODUCTION | live service | real minimized data | least privilege | managed deployment + Admin UI |

## Required capabilities

Production target پارس‌پک با MySQL 8 است. پلن نهایی MUST همچنین PHP 8.5، Laravel 13 requirements، scheduled tasks، queue processing قابل اتکا، HTTPS، releasable deployments، secret management، object-storage access، backup export و health monitoring را فراهم کند. وجود MySQL 8 تأیید شده؛ باقی capabilityها پیش از Implementation باید از پنل/قرارداد سرویس مستند شوند.

Host/provider نام مشخصی در Domain یا code ندارد. انتخاب Provider در Release planning با اثبات همین capabilityها انجام می‌شود.

## Deployment contract

- Build و test در CI انجام می‌شود؛ Production dependency build روی درخواست مالک انجام نمی‌شود.
- Migration با deploy hook مدیریت‌شده اجرا می‌شود و rollback/runbook دارد.
- Release از artifact immutable، environment config جدا و health check پس از deploy استفاده می‌کند.
- Owner از Dashboard برای مشاهده وضعیت، انتخاب release تأییدشده و rollback استفاده می‌کند؛ Terminal پیش‌نیاز عملیات عادی نیست.

## Configuration and secrets

- `.env` و credentialها commit نمی‌شوند.
- Local example فقط نام متغیر و مقدار غیرحساس نمونه دارد.
- Production keyها در secret manager/control panel و با دسترسی محدود نگهداری می‌شوند.
- Debug در Staging و Production خاموش است؛ logها PII کودک یا token کامل ندارند.

## Data isolation

- هر Environment Database، bucket، cache namespace، queue و key مستقل دارد.
- Production dump خام وارد Development یا Local نمی‌شود.
- Seedهای Development/Testing deterministic و synthetic هستند.
- Staging migration پیش از Production اجرا و smoke test می‌شود.

## Owner-safe operations

Admin UI باید Publish/Unpublish بازی، Media review، user management، گزارش، queue failureهای مجاز و audit را پوشش دهد. Backup، certificate و deploy با Control Panel یا Automation مدیریت‌شده انجام می‌شوند؛ دستور Terminal برای مالک مستند نمی‌شود.
