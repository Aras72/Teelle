# AGENTS.md

این فایل بر تمام Repository حاکم است.

## شروع هر Task

1. `PROJECT-STATUS.md` و `DECISIONS.md` را بخوان.
2. اسناد فاز و Task مرتبط را بخوان.
3. در صورت نیاز سه سند اصلی `docs/00-foundation/source-materials/` را از خود Repository بخوان؛ به نسخه پیوست یا Downloads اتکا نکن.
4. وضعیت Git، Branch، Remote و تغییرات ناشناخته را پیش از ویرایش بررسی کن.
5. تغییرات کاربر یا Agentهای دیگر را حفظ کن.

## قوانین قطعی

1. Documentation منبع حقیقت است.
2. Requirement اختراع نکن و Unknown را پنهان نکن.
3. تصمیم `APPROVED` یا `FROZEN` را بی‌صدا تغییر نده.
4. Implementation تا عبور Gateهای برنامه‌ریزی `LOCKED` است.
5. هسته وب MUST با PHP و Laravel ساخته شود.
6. وب‌سایت Responsive کامل MUST پیش از هر Mobile work تحویل و تأیید شود.
7. Mobile، فقط در صورت Scope صریح، MUST با TWA و پس از `WEBSITE COMPLETE = PASS` ساخته شود.
8. مالک یا کاربر عادی MUST برای استفاده یا مدیریت روزمره به Terminal وابسته نشود.
9. Matching و Recommendation MUST قطعی، Metadata-based و فقط روی بازی‌های Published/Reviewed باشد؛ AI داخل محصول حق تولید، انتخاب، بازنویسی یا رتبه‌بندی بازی را ندارد.
10. Safety هرگز پشت Paywall نمی‌رود و Ruleها برای ساخت نتیجه به‌طور پنهانی Relax نمی‌شوند.
11. Motion بخشی از هویت Brand است؛ هر Animation باید هدف داشته باشد و Accessibility/Performance را رعایت کند.
12. تیله تعاملی Homepage در `DEC-003` یک Requirement مصوب است.
13. Task را خارج از Scope گسترش نده.
14. شکست تست یا بررسی را پنهان نکن؛ بررسی‌نشده را `NOT RUN` یا `UNVERIFIED` اعلام کن.
15. تغییر رفتار، معماری، Schema، API، Security یا Deployment را با مستندات همگام کن.
16. هر Task باید Diff review، تست‌های مرتبط و گزارش صادقانه داشته باشد.
17. Commit محلی معادل تحویل نیست؛ Push و تطبیق HEAD محلی با Remote جداگانه بررسی شود.

## سلسله‌مراتب تعارض

1. آخرین دستور صریح مالک
2. آخرین تصمیم APPROVED/FROZEN
3. مستندات APPROVED
4. Implementation فعلی
5. مستندات Draft
6. تاریخچه گفتگو
7. فرضیات AI

تعارض را بی‌صدا حل نکن؛ آن را تحلیل و در `DECISIONS.md` ثبت کن.
