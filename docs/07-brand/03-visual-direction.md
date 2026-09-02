# Visual Direction - تیله

Status: APPROVED
Phase: 07 - BRAND IDENTITY

## Design read

Modern Nostalgia × Playful Intelligence برای محصولی adult-facing درباره کودکان.

## Dials

- `DESIGN_VARIANCE: 8`
- `MOTION_INTENSITY: 7`
- `VISUAL_DENSITY: 4`

## Palette

- Petrol `#123F46`: پایه، متن قوی و سطوح عمیق
- Cream `#FFF8E6`: سطح اصلی گرم
- Peach `#FAE297`: نور و سطح حمایتی
- Ruby `#E83346`: Accent یگانه برای CTA، Focus مهم و Jigari

هر صفحه Theme واحد دارد. تغییر رنگ‌ها در همان خانواده انجام می‌شود، نه وارونگی تصادفی Sectionها.

## Typography

- خانواده پایه فارسی: Vazirmatn Variable، self-hosted، با fallback `Tahoma, Arial, sans-serif`
- Display: همان خانواده با وزن ۷۰۰ تا ۹۰۰؛ Serif تزئینی استفاده نمی‌شود.
- Body: وزن ۴۰۰ تا ۵۰۰، line-height خوانا و طول خط محدود.
- اعداد و UI از Stylistic set مصوب و یکسان استفاده می‌کنند.

Source: [Vazirmatn official repository](https://github.com/rastikerdar/vazirmatn)؛ مجوز OFL 1.1 و فایل Variable باید هنگام Implementation داخل Repository vendored و License آن حفظ شود.

## Shape and material

- تیله: دایره دقیق، شیشه ضخیم، شکست نور، Caustic و رگه داخلی.
- UI: Radius نرم ۱۴ تا ۱۸px برای Container، Pill فقط برای Button/Chip، Input برابر ۱۲px.
- Shadow تیره خالص ممنوع؛ سایه‌ها با Petrol tint می‌شوند.
- Glass فقط جایی استفاده می‌شود که هویت یا Layering واقعی دارد، نه روی همه Cardها.

## Imagery and iconography

- تصویر واقعی یا Illustration معنادار از بازی، بدون کودک نمایشی اغراق‌شده.
- Iconها از یک خانواده خطی واحد با ضخامت ثابت انتخاب می‌شوند.
- تیله جای Icon عملکردی را نمی‌گیرد.

## Motion

- Ambient: تنفس نوری و چرخش بسیار آهسته برای حضور زنده.
- Feedback: فشردن، کشیدن، انتخاب و تغییر State با وزن فیزیکی نرم.
- Transition: حرکت قوسی کوتاه و deceleration شبیه قل‌خوردن، بدون Scroll hijack.
- فقط `transform` و `opacity` در مسیرهای پرتکرار animate می‌شوند.
- reduced-motion حرکت دائمی و دامنه بزرگ را حذف می‌کند ولی Focus و State را حفظ می‌کند.
