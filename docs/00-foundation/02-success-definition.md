# Success Definition — تیله

Status: APPROVED
Phase: 00 — PROJECT FOUNDATION

## موفقیت پروژه

تیله زمانی موفق است که خانواده‌ها را با اصطکاک کم از صفحه‌نمایش به شروع بازی واقعی برساند و این رفتار به‌صورت تکرارشونده، امن و قابل اندازه‌گیری رخ دهد.

## North Star

`Play Starts` — تعداد دفعات ثبت رویداد `started`، همان عدد عمومی Heartbeat تیله.

رویدادهای `completed` و `rated` جداگانه برای تحلیل داخلی ثبت می‌شوند و نباید با North Star عمومی مخلوط شوند.

## موفقیت MVP

- کاربر Guest بتواند بدون Login مسیر Quick Match تا Start را کامل کند.
- Screen-to-Play هدف کمتر از ۶۰ ثانیه و Quick Match ترجیحاً کمتر از ۳۰ ثانیه باشد.
- Matching فقط روی بازی‌های Published/Reviewed و با Rules قطعی انجام شود.
- سه نتیجه مناسب، متنوع و explainable نمایش داده شود.
- No-result بدون Relax پنهانی Ruleها مدیریت شود.
- جریان started → completed → rated به‌درستی و قابل تحلیل ثبت شود.
- Library و Coverage برای Scope مصوب MVP Gate محتوایی را پاس کنند.
- تجربه UI/UX و Motion، هویت تیله را منتقل کند و Accessibility/Performance قابل قبول باشد.

## موفقیت ۶ ماهه

مقادیر عددی Target پس از Research، Beta baseline و تعریف KPI Framework تعیین می‌شوند. تا آن زمان موارد زیر معیار جهت هستند:

- رشد Play Starts و Plays per Active Family
- بازگشت خانواده در بازه‌های ۷ و ۳۰ روزه
- نرخ قابل قبول Quick Match → Start
- کاهش No-result و Coverage gaps
- Completion و Rating کافی برای یادگیری محصول
- Conversion و تمدید سالم تیله جیگری بدون تضعیف Core رایگان

## موفقیت یک‌ساله

Target عددی هنوز UNKNOWN است و نباید اختراع شود. تعریف نهایی باید شامل Retention، Play frequency، Quality/Safety، Revenue sustainability و Operational reliability باشد.

## نشانه‌های شکست ایده

- کاربران به سه پیشنهاد می‌رسند اما بازی را شروع نمی‌کنند.
- برای گرفتن نتیجه مجبور به جست‌وجو یا ورود اطلاعات طولانی می‌شوند.
- Coverage یا Safety کتابخانه برای Situationهای اصلی کافی نیست.
- Matching قطعی اعتماد کاربر را به‌طور پایدار از دست می‌دهد.
- رشد محصول به Scroll، Notification spam یا Engagement داخل صفحه وابسته می‌شود.
- عضویت پولی Core Value رایگان یا Safety را تخریب می‌کند.
- هزینه محتوای کنترل‌شده و عملیات محصول از مدل درآمدی قابل پشتیبانی نیست.
