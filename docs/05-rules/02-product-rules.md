# Product Rules — تیله

Status: IN_REVIEW
Phase: 05 — PRODUCT RULES

## Quick Match input

- `PR-QM-001`: Guest MUST بدون Login بتواند Match را آغاز کند.
- `PR-QM-002`: Age MUST اجباری و به ماه نرمال شود.
- `PR-QM-003`: Input UI SHOULD برای ۶ تا ۲۳ ماه از ماه و برای ۲ تا ۱۲ سال از سال/ماه قابل فهم استفاده کند؛ Domain همیشه ماه ذخیره می‌کند.
- `PR-QM-004`: Context MUST فقط داده لازم برای Hard Filter و Ranking مصوب را بگیرد.
- `PR-QM-005`: سیستم MUST داده قابل استنتاج را دوباره نپرسد.
- `PR-QM-006`: Age تنها سؤال ثابت است؛ سؤال‌های Situation، Duration، Location/Space، Required materials، Players/Adult presence، Energy و Mood MUST فقط در صورت نیاز Context پرسیده شوند.
- `PR-QM-007`: Mood و Energy MUST دو ورودی مستقل باشند.
- `PR-QM-008`: Noise و Mess MAY به‌صورت سؤال شرطی ظاهر شوند و MUST به‌صورت پیش‌فرض فرم را طولانی نکنند.

## Matching pipeline

ترتیب ثابت:

1. Validate context
2. Enforce supported age
3. Select Published/Reviewed games
4. Apply Safety hard filters
5. Apply age hard filter
6. Apply required-player and required-material hard filters
7. Apply location/space hard filters
8. Apply deterministic scoring
9. Apply diversity rules
10. Return exactly three or explicit no-result

- `PR-MATCH-001`: Hard Filter failure MUST NOT با Weight جبران شود.
- `PR-MATCH-002`: Tie-breaking MUST deterministic و ثبت‌شده باشد.
- `PR-MATCH-003`: History، Interest، Mood و Energy MAY فقط با Weight مصوب اثر بگذارند.
- `PR-MATCH-004`: Explanation MUST از Metadata/Ruleهای واقعی خروجی ساخته شود.
- `PR-MATCH-005`: Result موفق MUST دقیقاً سه Game ID یکتا داشته باشد.
- `PR-MATCH-006`: Variationهای یک Core Game MUST NOT هر سه Slot را اشغال کنند.

## No-result

- `PR-NR-001`: کمتر از سه Survivor MUST نتیجه موفق معرفی نشود.
- `PR-NR-002`: سیستم MUST no-result را صریح اعلام کند.
- `PR-NR-003`: سیستم MAY یک Constraint امن و قابل تغییر را پیشنهاد دهد، اما تغییر فقط با اقدام آگاهانه کاربر انجام می‌شود.
- `PR-NR-004`: Safety و Age MUST NOT قابل Relax باشند.
- `PR-NR-005`: no-result MUST برای Coverage analysis ثبت شود بدون ذخیره PII غیرضروری.

## Result and play

- `PR-RES-001`: Result card MUST زمان، وسایل ضروری، محیط و دلیل تناسب را پیش از Start روشن کند.
- `PR-RES-002`: Safety warning MUST پیش از Start قابل مشاهده باشد.
- `PR-PLAY-001`: ثبت `started` MUST idempotent باشد.
- `PR-PLAY-002`: `completed` MUST فقط پس از `started` معتبر باشد.
- `PR-PLAY-003`: `rated` MUST به Session معتبر مرتبط باشد و MAY پس از completed ثبت شود.
- `PR-PLAY-004`: Reminder MUST opt-in/consent-aware باشد.

## Coverage

- `PR-COV-001`: Golden Coverage Matrix MUST Age bandها و Situationهای Critical را مشخص کند.
- `PR-COV-002`: هر سلول Critical MUST حداقل سه Survivor بعد از Baseline hard filters داشته باشد.
- `PR-COV-003`: Gap بحرانی MUST آن Context را از Claim پشتیبانی خارج کند.
- `PR-COV-004`: Library count به‌تنهایی MUST NOT جای Coverage validation را بگیرد.

## Motion and Homepage

- `PR-MOT-001`: Marble Hero MUST بدون تعامل نیز Ambient motion کنترل‌شده داشته باشد.
- `PR-MOT-002`: Pointer movement MUST جهت‌گیری/چرخش محدود و نرم ایجاد کند.
- `PR-MOT-003`: Direct drag/touch MUST کنترل چرخش را به کاربر بدهد.
- `PR-MOT-004`: Keyboard alternative و reduced-motion MUST وجود داشته باشند.
- `PR-MOT-005`: Motion MUST CTA را نپوشاند، Input را Block نکند و Scroll hijack نسازد.

## Theme and heartbeat

- `PR-THEME-001`: Theme انتخاب‌شده MUST از Home تا Match، Result، Detail، Active Play، Account، Jigari و Admin ادامه یابد.
- `PR-THEME-002`: تغییر Theme MAY در Header/Settings انجام شود و MUST بدون Flash ناسازگار اعمال شود.
- `PR-THEME-003`: Dark surfaces MUST تمیز، بدون Noise/Grain/Pattern تکراری و با Contrast قابل اندازه‌گیری باشند.
- `PR-HB-001`: Heartbeat عمومی MUST فقط شمارش aggregate رویدادهای معتبر `started` را نمایش دهد.
- `PR-HB-002`: عدد Mockup MUST به‌عنوان Sample ثبت شود و MUST در Implementation hard-code نشود.
