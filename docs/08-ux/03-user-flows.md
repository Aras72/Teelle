# User Flows - تیله

Status: DRAFT FOR REVIEW
Phase: 08 - UI/UX DESIGN

## Guest Quick Match

Home -> چی بازی کنیم؟ -> سن -> Situation -> Context لازم و تطبیقی -> Match -> سه Result -> Detail -> شروع بازی -> Active Play -> بازی کردیم، برگشتیم -> Feedback اختیاری

Branchها:

- سن خارج Scope -> توضیح محترمانه، بدون Result
- داده Required ناشناخته -> سؤال حداقلی
- کمتر از سه Survivor -> no-result و Constraint امن قابل تغییر
- بازی Unpublish شود -> توضیح و Re-match

## Adaptive context policy

- همیشه: Age
- معمولاً: Situation و Duration
- در صورت نیاز Hard filter: Location/Space، Required materials و Players/Adult presence
- در صورت اثر Ranking مصوب: Energy و Mood به‌صورت مستقل
- شرطی: Noise و Mess

سیستم همه سؤال‌ها را در هر Match نمایش نمی‌دهد. پاسخ قبلی یا Situation می‌تواند سؤال بعدی را حذف یا لازم کند.

## Theme continuity

Theme انتخاب‌شده یک State سطح Application است و در تمام Branchهای Guest، Account و Jigari حفظ می‌شود. هیچ Transition بین Screenها حق بازگرداندن ناخواسته Theme به Light را ندارد.

## Signup after value

Result/Complete -> دعوت به ذخیره -> Signup -> Merge Session -> Saved/History

رد Signup کاربر را از Core flow خارج نمی‌کند.

## Multi-child Jigari

Account -> انتخاب دو یا چند Child Profile -> Context مشترک -> تقاطع Eligibility/Safety -> سه Result مشترک یا no-result -> Detail -> Start

## Purchase

Jigari -> انتخاب ۳/۶/۱۲ ماه -> خلاصه شفاف -> درگاه -> Pending/Success/Failed -> Entitlement server-confirmed

## Admin publish

Draft -> Validate metadata -> Editorial review -> Safety review -> Coverage check -> Publish -> Audit log
