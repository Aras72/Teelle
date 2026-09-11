# Data Retention

Status: ACCEPTED OPERATIONAL BASELINE WITH LEGAL FINANCIAL HOLD
Phase: 10 - DATA

## Rules

- Collect only data tied to a documented Product، Safety، Security، Support or legal purpose.
- Retention duration is counted from last relevant activity or legal event.
- Deletion request removes or anonymizes eligible data؛ financial/security records under a lawful hold are restricted instead of silently erased.
- Backup expiry follows its own bounded schedule and deletion propagates on normal rotation.

## Proposed classes

| Data class | Baseline behavior | Final duration owner |
| --- | --- | --- |
| Guest Match context | short TTL then delete/anonymize | Product + Privacy in PHASE 11 |
| Account and Child Profile | while account active؛ deletion workflow on request | Privacy/legal |
| Play events | pseudonymize for product metrics after account deletion | Product + Privacy |
| Payment/accounting | retain only statutory minimum | Legal/accounting market decision |
| Audit/security logs | bounded security window، access restricted | Security/legal |
| Raw application logs | shortest operationally useful window | Operations/Security |
| Aggregated anonymous metrics | may persist if re-identification is not reasonably possible | Privacy review |

## Prohibitions

- Exact child birth date، address، school، photo or family details are not retained without a separately approved need.
- Logs must not contain password، token، payment credential، full cookie or free-form child PII.
- Analytics export must not expose small cohorts that enable re-identification.

PHASE 11 defaults عملیاتی 30 روز برای Guest context/log، 90 روز برای Security events، حداکثر 30 روز برای حذف eligible account data و 35 روز برای Backup rotation تعیین کرد. مدت Payment/accounting فقط پس از تأیید الزام قانونی بازار هدف Freeze می‌شود.

Prompt 013 درخواست حذف نسخه‌دار با `scheduled_for = requested_at + 30 days` و لغو Self-service را اجرا کرد، اما عمداً هیچ داده‌ای را پیش از Executor مستقل پاک یا ناشناس نمی‌کند. Executor، Backup propagation و Legal hold باید در Slice و Gate عملیاتی جداگانه آزموده شوند.
