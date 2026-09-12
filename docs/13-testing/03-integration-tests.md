# Integration Tests

Date: 2026-09-10
Status: MYSQL 8 REGRESSION PASS

نسخه رسمی MySQL Community Server `8.4.11` با checksum رسمی در محیط موقت ایزوله نصب و روی `127.0.0.1:14008` اجرا شد. دیتابیس disposable با نام `teelle_phase15_test` و کاربر اختصاصی بدون ذخیره Credential در Repository ساخته شد.

پورت اعلام‌شده `127.0.0.1:3500` سالم و در حال Listen است، اما handshake آن نسخه `26.7.0` را گزارش می‌کند و بنابراین برای assertion قطعی MySQL 8 استفاده نشد. سرویس و داده‌های آن بدون تغییر باقی ماندند.

## Evidence

- `MySqlDataFoundationTest`: `8 passed`، `37 assertions`
- Full Laravel regression after Prompt 011: `64 passed`، `483 assertions`
- Full Laravel regression after Prompt 017: `92 passed`، `764 assertions`
- Full Laravel regression after Prompt 018: `94 passed`، `776 assertions`
- Full Laravel regression after Prompt 019 CI contract: `95 passed`، `800 assertions`
- Editorial Collections + MySQL schema: `12 passed`، `67 assertions`
- Order-dependent Content Admin + Collections regression: `14 passed`، `80 assertions`
- Jigari entitlement and Child Profile authorization: `7 passed`، `50 assertions`
- Server version assertion: `8.4.11`
- migration، Seeder idempotency و تمام integrity constraintها: PASS
- Auth و Guest merge، Content/Admin، Quick Match، Result/Play، Heartbeat، Jigari Child Profiles و Editorial Collections: PASS

در اجرای اولیه full suite، اجرای `migrate:fresh` داخل کلاس schema وضعیت مشترک `RefreshDatabase` را برای تست‌های بعدی آلوده کرد. تست schema به `RefreshDatabase` و seed تراکنشی هر تست منتقل شد؛ سپس full suite بدون Skip یا Failure دوباره اجرا و پاس شد.

این Evidence گیت دیتابیس و Integration محلی را می‌بندد، اما نسخه دقیق MySQL هاست پارس‌پک و migration rehearsal روی محیط همسان Staging هنوز `NOT VERIFIED` است.
