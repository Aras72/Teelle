# Security Testing

Date: 2026-09-10
Status: CONDITIONAL / STAGING REQUIRED

## PASS

- `composer audit --locked`: بدون Advisory یا Package abandoned
- `pnpm audit --prod --audit-level high`: صفر Vulnerability در همه Severityها
- Route review: Account routes دارای Auth و Verified middleware؛ Auth/Recovery/Match/Play دارای Rate limiter؛ Admin دارای Staff middleware
- Middleware سراسری Laravel، CSP پایه، anti-framing، MIME nosniff، Referrer Policy و Permissions Policy را روی Web response اعمال می‌کند
- HSTS فقط برای Request امن در Environment تولید فعال می‌شود و شاخه مثبت/منفی آن با Feature test پوشش دارد
- تست‌های قبلی Scope، Authorization، idempotency، fail-closed publication و Guest merge در گزارش Promptهای مربوط ثبت شده‌اند

## باز یا نامعتبر در Local

- پاسخ HTTP قبل از Remediation Headerهای پایه را نداشت؛ Middleware افزوده شد، تست Feature پاس شد و پاسخ Local همه Headerهای فهرست‌شده را برگرداند. رفتار Edge/Proxy در Staging همچنان باید مستقل تأیید شود.
- HTTPS/HSTS و Cookie `Secure` روی Local HTTP قابل ارزیابی نیستند.
- SMTP، SPF/DKIM/DMARC و SMS Provider تأیید نشده‌اند.
- تست نفوذ، DAST و Upload malware scanner واقعی: NOT RUN.
- Regression کامل دیتابیسی این اجرا به‌علت نبود Credential MySQL 8 پاس نشده است.

هیچ Secret یا Credential در گزارش QA ثبت نمی‌شود.
