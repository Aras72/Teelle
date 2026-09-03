# Game Content Visual Contract

Status: APPROVED BY OWNER
Phase: 08 - UI/UX DESIGN

## Purpose

با بزرگ‌شدن کتابخانه بازی‌ها، تصویر و توضیح هر بازی MUST در همان خانواده بصری و نوشتاری نمونه‌های تأییدشده باقی بماند. افزایش تعداد بازی‌ها نباید صفحه را به مجموعه‌ای ناهمگون از تصاویر و متن‌ها تبدیل کند.

## Image system

- تصویر اصلی هر بازی Object-led و شبیه یک Diorama کوچک، ملموس و قابل ساخت است.
- تیله فقط وقتی نقش معنایی دارد وارد تصویر می‌شود و به Stamp تزئینی اجباری تبدیل نمی‌شود.
- Palette از Petrol، Ruby، Peach و رنگ‌های شیشه‌ای تیله پیروی می‌کند.
- نور نرم، Material واقعی و سایه کنترل‌شده حفظ می‌شود؛ Noise، Watermark و متن داخل تصویر ممنوع است.
- Card source با نسبت 1:1 و Detail source با Crop امن برای 4:5، 1:1 و 16:9 تولید می‌شود.
- Subject اصلی در Safe area مرکزی باقی می‌ماند تا Cropهای Responsive معنی تصویر را حذف نکنند.
- Light و Dark از یک Art direction استفاده می‌کنند؛ تغییر Theme هویت بازی را عوض نمی‌کند.

## Copy system

- Title کوتاه، متمایز و مناسب فارسی است.
- Summary در یک جمله می‌گوید بازی چیست، نه اینکه صرفاً هیجان ایجاد کند.
- Metadata شامل زمان، وسیله ضروری، محیط، تعداد بازیکن و نظارت بزرگسال از داده ساختاریافته می‌آید.
- Reason for fit فقط از Rule و Metadata واقعی ساخته می‌شود.
- Safety copy مشخص، عملی و قبل از Start قابل مشاهده است.
- توضیح‌ها در Runtime توسط AI ساخته یا بازنویسی نمی‌شوند.

## Required asset package per game

1. نام و Summary تأییدشده
2. تصویر Master بدون متن و Watermark
3. Cropهای Card و Detail
4. Alt text کاربردی
5. Metadata کامل و قابل اعتبارسنجی
6. Safety note و Required materials
7. Review status و نسخه محتوا

## Quality gate

- بازی بدون تصویر یا Metadata کامل Publish نمی‌شود.
- Style، Crop، خوانایی در دو Theme و صحت Alt text پیش از Publish بررسی می‌شوند.
- تصویر نباید وسیله‌ای را نشان دهد که در Required materials ذکر نشده است.
- تصویر و توضیح نباید Safety، سن یا تعداد بازیکن را گمراه‌کننده نمایش دهند.
