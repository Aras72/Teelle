# راهنمای انتقال دستی به پارس‌پک

Status: RELEASE-CANDIDATE PROCEDURE / PRODUCTION EVIDENCE PENDING

## مشخصات تأییدشده مالک

- دامنه نهایی: `https://teelle.ir`
- PHP هاست: 8.4
- MySQL: 8
- دسترسی SSH/Terminal: ندارد
- انتقال فایل و پایگاه داده: دستی از پنل
- SSL رایگان: هنگام اتصال دامنه فعال می‌شود
- SMTP: پس از انتقال توسط مالک ارائه می‌شود

## فایل آماده انتقال

پس از PASS شدن Workflow با نام `Quality` در GitHub، از همان Run بخش Artifacts فایل `teelle-web-php84-...` را دانلود کنید. این بسته `vendor` و فایل‌های Build شده را دارد و به Composer یا pnpm روی هاست نیاز ندارد. فایل `.env` و هیچ Secretی داخل Artifact نیست.

## ترتیب انتقال

1. از فایل‌ها و MySQL فعلی نسخه پشتیبان بگیرید.
2. Artifact تأییدشده همان Commit را دانلود و از حالت فشرده خارج کنید.
3. Document Root دامنه باید دقیقاً پوشه `public` پروژه باشد. اگر پنل اجازه این تنظیم را نمی‌دهد، انتشار متوقف شود؛ قرار دادن کل Laravel در `public_html` امن نیست.
4. فایل `.env.production.example` را در هاست با نام `.env` بسازید و مقادیر Secret را فقط در پنل وارد کنید.
5. پایگاه داده سازگار همان نسخه را از phpMyAdmin یا ابزار Import پنل منتقل کنید. قبل از جایگزینی، نسخه پشتیبان نگه دارید.
6. نوشتن برای `storage` و `bootstrap/cache` را فقط به کاربر PHP بدهید.
7. دامنه را وصل و SSL را فعال کنید؛ سپس `APP_URL=https://teelle.ir` و Cookie امن را تنظیم کنید.
8. SMTP، SPF، DKIM و DMARC را تنظیم و ثبت‌نام، تأیید ایمیل و بازیابی رمز را با صندوق واقعی تست کنید.
9. Cron پنل باید هر دقیقه Scheduler لاراول را اجرا کند. اگر پنل هیچ روش Cron یا Scheduled Task ندارد، انتشار متوقف شود چون سهمیه روزانه و کارهای زمان‌بندی‌شده قابل اتکا نیستند.

## کنترل پس از انتقال

- `/up` پاسخ موفق دارد.
- صفحه اصلی، Match، Login، Register، Account و Admin روی HTTPS باز می‌شوند.
- ثبت‌نام، ایمیل تأیید و بازیابی رمز واقعاً به Inbox می‌رسد.
- اتصال MySQL 8، نوشتن Session و Upload تصویر موفق است.
- Headerهای امنیتی، Service Worker، Offline و نصب PWA روی دامنه واقعی بررسی می‌شوند.
- Backup و بازگردانی واقعی و مسیر Rollback یک بار تمرین می‌شوند.

تا انجام این کنترل‌ها، بسته فقط Release Candidate است و Phase 15/16 Production PASS محسوب نمی‌شود.
