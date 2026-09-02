# Deterministic Ranking Contract - تیله

Status: APPROVED WITH CALIBRATION HOLD
Phase: 05 - PRODUCT RULES

## قرارداد قطعی

- Hard filters پیش از Scoring اجرا می‌شوند و امتیاز آن‌ها را خنثی نمی‌کند.
- Score فقط از Metadata مصوب و Context صریح ساخته می‌شود.
- Dimensionهای مجاز MVP: Situation fit، Location/Space fit، Duration fit، Energy fit، Mood fit، Adult involvement fit و History diversity.
- هر Dimension تابع امتیازدهی نسخه‌دار، قابل تست و قابل توضیح دارد.
- وزن‌ها عدد صحیح نامنفی‌اند و مجموع آن‌ها دقیقاً ۱۰۰ است.
- Tie-break به‌ترتیب `review_quality`، کمترین تکرار در History، سپس `game_id` پایدار انجام می‌شود.
- تغییر Weight نیازمند Golden-set evaluation، ثبت Decision و انتشار نسخه جدید Rule set است.
- Revenue، Jigari، Sponsor و Campaign حق حضور در Score را ندارند.

## Calibration hold

عدد دقیق Weightها عمداً هنوز FROZEN نیست. تعیین عدد بدون Golden set و Coverage evidence، دقت کاذب می‌سازد. این Hold مانع Brand و UI/UX نیست، اما مانع Implementation الگوریتم Matching است.

## Acceptance برای رفع Hold

- Golden set شامل Age bandها و Situationهای Critical باشد.
- حداقل دو Weight candidate با Expected ordering مقایسه شوند.
- no-result، diversity و Explanation روی نمونه‌ها ارزیابی شوند.
- Weight منتخب در Decision Log و fixtureهای تست ثبت شود.
