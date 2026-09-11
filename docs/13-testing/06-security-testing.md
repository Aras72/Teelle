# Security Testing

Date: 2026-09-11
Status: CONDITIONAL / STAGING REQUIRED

## PASS

- `composer audit --locked`: اجرای تازه 2026-09-11 بدون Advisory
- `pnpm audit --prod --audit-level high`: اجرای تازه 2026-09-11 بدون Vulnerability شناخته‌شده
- Route review: Account routes دارای Auth و Verified middleware؛ Auth/Recovery/Match/Play دارای Rate limiter؛ Admin دارای Staff middleware
- Middleware سراسری Laravel، CSP پایه، anti-framing، MIME nosniff، Referrer Policy و Permissions Policy را روی Web response اعمال می‌کند
- HSTS فقط برای Request امن در Environment تولید فعال می‌شود و شاخه مثبت/منفی آن با Feature test پوشش دارد
- تست‌های قبلی Scope، Authorization، idempotency، fail-closed publication و Guest merge در گزارش Promptهای مربوط ثبت شده‌اند

## باز یا نامعتبر در Local

- پاسخ HTTP قبل از Remediation Headerهای پایه را نداشت؛ Middleware افزوده شد، تست Feature پاس شد و پاسخ Local همه Headerهای فهرست‌شده را برگرداند. رفتار Edge/Proxy در Staging همچنان باید مستقل تأیید شود.
- HTTPS/HSTS و Cookie `Secure` روی Local HTTP قابل ارزیابی نیستند.
- SMTP، SPF/DKIM/DMARC و SMS Provider تأیید نشده‌اند.
- تست نفوذ، DAST و Upload malware scanner واقعی: NOT RUN.
- Regression کامل جاری روی MySQL 8.4.11 ایزوله با قرارداد دیتابیس فعال برابر 71 test / 605 assertion و بدون Error، Failure یا Skip PASS است.

هیچ Secret یا Credential در گزارش QA ثبت نمی‌شود.
