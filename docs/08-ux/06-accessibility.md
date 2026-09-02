# Accessibility - تیله

Status: DRAFT FOR REVIEW
Phase: 08 - UI/UX DESIGN

## Keyboard and focus

- تمام Actionها با Tab/Shift+Tab و Enter/Space کار می‌کنند.
- Focus ring با Ruby و Contrast مناسب، بدون حذف Outline جایگزین‌نشده.
- Marble کنترل جداگانه و Label روشن دارد و CTA را از ترتیب Focus خارج نمی‌کند.
- Escape تعامل Drag/rotation را لغو می‌کند.

## Screen reader

- Landmarkهای Header، Main، Nav و Footer درست‌اند.
- Heading hierarchy پیوسته است.
- وضعیت Match، Loading، no-result و Event success با live region مناسب اعلام می‌شود.
- Marble تزئینی نیست: نام، راهنما و State کوتاه دارد؛ جزئیات بصری زائد خوانده نمی‌شوند.

## Motion

- `prefers-reduced-motion` حرکت دائمی، Parallax و Momentum را حذف می‌کند.
- reduced-motion نسخه ثابت هویت را حفظ می‌کند و هیچ Function حذف نمی‌شود.
- Pause در tab background و کاهش کیفیت روی دستگاه ضعیف الزامی است.

## Contrast and input

- متن عادی حداقل WCAG AA و هدف Body برابر AAA است.
- Touch target حداقل 44×44 CSS pixel.
- Error فقط با رنگ منتقل نمی‌شود.
- Zoom 200% و Reflow تا عرض 320px بدون از دست‌رفتن Action اصلی بررسی می‌شود.

## RTL and localization

- `dir=rtl` پایه است؛ رشته‌های عددی/فنی isolate می‌شوند.
- اعداد ورودی فارسی و لاتین Normalize می‌شوند.
- ترجمه بلند Safety، CTA یا Start را پنهان نمی‌کند.
