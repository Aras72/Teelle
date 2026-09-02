# Validation Plan — تیله

Status: APPROVED
Phase: 03 — IDEA VALIDATION

## Validation objective

مشخص شود آیا تیله برای همراه کودک یک مشکل واقعی، پرتکرار و باارزش را حل می‌کند و آیا راه‌حل «Context کوتاه → سه پیشنهاد Reviewed → Play Start» نسبت به Workaroundهای فعلی به‌اندازه کافی بهتر است.

## Critical hypotheses

### VAL-001 — Problem frequency

Hypothesis: اصطکاک انتخاب بازی حداقل در بخشی از والدین/Caregiverهای هدف پرتکرار و آزاردهنده است.

Evidence required:

- مصاحبه مبتنی بر رفتار گذشته، نه نظر کلی
- نمونه‌هایی از آخرین Situation واقعی
- Workaround، شدت و پیامد عدم بازی

Recommended threshold: دست‌کم ۸ نفر از ۱۲ مصاحبه‌شونده یک نمونه واقعی در ۱۴ روز اخیر گزارش کنند و حداقل ۵ نفر Workaround نامطلوب یا رهاکردن بازی را توضیح دهند.

Failure signal: مشکل عمدتاً فرضی، کم‌تکرار یا به‌سادگی با راه‌حل موجود حل می‌شود.

### VAL-002 — Three-choice value

Hypothesis: سه پیشنهاد Contextual نسبت به List بلند، تصمیم را سریع‌تر و Start intent را بیشتر می‌کند.

Test:

- Prototype A: فهرست ۱۲ گزینه
- Prototype B: سه گزینه با دلیل تناسب
- Task ثابت و ترتیب تصادفی

Metrics:

- Time-to-choice
- Choice confidence
- Start intent
- Abandonment

Recommended threshold: Prototype B باید Median time-to-choice کمتر و Confidence حداقل هم‌سطح A داشته باشد؛ افت اعتماد برای سرعت بیشتر قابل قبول نیست.

### VAL-003 — Context tolerance

Hypothesis: کاربر حاضر است تعداد کمی ورودی Context بدهد اگر نتیجه به‌وضوح مناسب‌تر شود.

Test: مقایسه ۳، ۵ و ۷ سؤال با سنجش Completion و perceived effort.

Guardrail: هیچ سؤال قابل استنتاج دوباره پرسیده نشود.

### VAL-004 — Deterministic relevance

Hypothesis: Rule Engine و Library کنترل‌شده بدون AI می‌تواند سه نتیجه قابل قبول بسازد.

Test:

- حداقل ۳۰ Golden Situation
- Review مستقل توسط دو Reviewer محتوا
- ثبت disagreement و no-result

Recommended threshold: Safety hard filter هیچ Failure بحرانی نداشته باشد؛ relevance threshold پس از Pilot baseline نهایی شود و نباید از قبل جعل شود.

### VAL-005 — Value before registration

Hypothesis: Guest می‌تواند پیش از Login ارزش Core را تجربه کند و Play Start رخ دهد.

Test: Concierge یا Prototype flow بدون Registration wall.

Metrics: Match completion، Result selection، simulated/actual Start، request to save.

### VAL-006 — Jigari willingness-to-pay

Hypothesis: Child Profile، History، Search/Filter و Weekly Plan برای بخشی از کاربران ارزش پرداخت دوره‌ای دارند.

Test:

- Problem interview پیش از Price prompt
- Feature ranking
- Van Westendorp یا Gabor-Granger سبک با Bias disclosure
- Fake-door فقط با Disclosure و بدون دریافت وجه

Failure signal: ارزش پولی فقط به Core Quick Match نسبت داده شود که باید رایگان بماند، یا دوره حداقل سه‌ماهه مانع غالب باشد.

### VAL-007 — Brand and interactive marble

Hypothesis: تیله تعاملی Hero تمایز، یادآوری و حس Brand را بالا می‌برد بدون کاهش فهم CTA یا Performance.

Test:

- Prototype Motion و reduced-motion
- Five-second comprehension
- CTA findability
- Pointer، Touch و Keyboard usability
- Performance profile روی دستگاه ضعیف

Guardrails: Motion sickness report، CTA confusion، input blocking و افت غیرقابل قبول Core Web Vitals.

## Participant segments

- والد کودک در سنین مختلف Core candidate
- والد کم‌انرژی پس از کار
- والد ضد Screen
- خانواده چندکودکی
- Caregiver موقت
- والد برنامه‌ریز

Age scope باید در Sampling عمداً متنوع باشد تا Beachhead از Evidence استخراج شود.

## Evidence integrity

- Consent و حذف PII غیرضروری الزامی است.
- سؤال Leading ممنوع است.
- «آیا از این استفاده می‌کنی؟» به‌تنهایی Evidence نیست.
- رفتار گذشته و Task behavior از Intent statement معتبرتر است.
- نتیجه Sample کوچک نباید به کل ایران تعمیم داده شود.
- Failure و contradiction باید همانند signal مثبت ثبت شوند.

## Stop conditions

- هر Safety concern بحرانی آزمون مرتبط را متوقف می‌کند.
- اگر Problem frequency پایین باشد، قبل از Feature expansion باید Segment/Pivot بررسی شود.
- اگر سه گزینه ارزش قابل سنجش نسازد، Result model باید بازنگری شود.
- اگر Rule Engine relevance کافی نسازد، Library/Taxonomy/Pipeline بررسی می‌شود؛ AI Recommendation جایگزین خودکار نیست.
