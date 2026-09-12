# E2E Tests

Date: 2026-09-10
Status: PARTIAL

## مرورگر واقعی اجراشده

- Homepage در Light و Dark: PASS
- نمایش و پخش Hero video و Poster fallback contract: PASS
- CTA به `/match` و نمایش سؤال 1 از 6: PASS
- ماندگاری Theme از Home به Match و Login: PASS
- Match در Light و Dark: PASS
- Login در Dark و Focus قابل مشاهده: PASS
- حساب عضو جیگری با Session واقعی: ساخت، ویرایش و بایگانی پروفایل کودک PASS
- Admin با Session و نقش واقعی: Dashboard، اعتبارسنجی Draft، Pilot preview و Coverage Matrix PASS
- Collections عمومی و Admin با Session بازبین: index، empty state و فرم Draft در 1440، 652 و 390، Light/Dark، RTL، Touch و Overflow PASS
- About عمومی در 390، 768 و 1440: Light/Dark، Hero دوخطی، CTA در viewport نخست، Navigation فعال، RTL، Touch و Overflow PASS
- Console در صفحات بررسی‌شده: بدون Error/Warning

## هنوز اجرا نشده

- تکمیل شش سؤال تا Result واقعی؛ Content/Ranking آماده نیست
- Start → Completion → Rating روی پیشنهاد Production
- Register → Verify → Login → Account با SMTP واقعی
- Recovery واقعی، Upload واقعی و سناریوهای Offline/PWA

عملیات داده‌نویس فقط روی MySQL موقت و ایزوله QA انجام شد؛ دیتابیس محلی مالک روی پورت 3500 لمس نشد.
