# Integration Tests

Date: 2026-09-10
Status: BLOCKED BY TEST DATABASE CREDENTIAL

اجرای `php artisan test` در محیط جاری 14 test را پاس کرد، 8 تست MySQL را طبق Guard صریح Skip کرد و 32 تست دیتابیسی پیش از منطق برنامه با `could not find driver` متوقف شدند. PHP جاری `pdo_mysql` دارد اما `pdo_sqlite` ندارد و `.env` فعلی روی SQLite است.

پورت `127.0.0.1:3500` در دسترس است، اما ورود فقط‌خواندنی Root بدون Password با `ERROR 1045` رد شد و Credential پروژه در Repository موجود نیست. بنابراین نسخه Server و Regression کامل در این اجرای QA تأیید نشدند.

برای بستن Gate باید یک Database ایزوله MySQL 8 با مجوز create/drop table در اختیار تست قرار گیرد و موارد زیر پاس شوند:

- migration و integrity هشت تست Schema
- Auth و Guest merge
- Content/Admin lifecycle و media quarantine
- Quick Match، Result، Play و Heartbeat
- full regression بدون Skip ناشی از Environment
