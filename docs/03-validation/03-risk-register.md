# Risk Register — تیله

Status: APPROVED BASELINE
Phase: 03 — IDEA VALIDATION

Probability و Impact در این نسخه Judgment اولیه‌اند و پس از Evidence باید بازبینی شوند.

| ID | Risk | Probability | Impact | Severity | Mitigation | Owner | Status |
|---|---|---|---|---|---|---|---|
| RSK-001 | Pain انتخاب بازی در ایران کم‌تکرار یا ضعیف باشد. | Medium | Critical | High | Problem interviews و Search study پیش از Product Definition | Product | OPEN |
| RSK-002 | کاربران سؤال‌های Context را رها کنند. | Medium | High | High | تست ۳/۵/۷ سؤال و progressive disclosure | UX | OPEN |
| RSK-003 | سه نتیجه relevance کافی نداشته باشند. | High | Critical | Critical | Golden situations، Coverage matrix و dual review | Product/Content | OPEN |
| RSK-004 | Safety metadata ناقص یا اشتباه باشد. | Medium | Critical | Critical | Structured safety، reviewer workflow و publish gate | Content/Safety | OPEN |
| RSK-005 | Library ۲۵۰ بازی هزینه یا زمان غیرقابل تحمل بخواهد. | High | High | High | Pilot throughput با ۲۰–۳۰ بازی و cost model | Operations | OPEN |
| RSK-006 | No-result rate در Situationهای مهم بالا باشد. | High | High | High | Coverage simulation و constraint-change UX بدون relax پنهانی | Domain/Content | OPEN |
| RSK-007 | ارزش Jigari برای پرداخت کافی نباشد. | Medium | Critical | High | Pricing interview و feature ranking | Business | OPEN |
| RSK-008 | حداقل دوره سه‌ماهه Conversion را تضعیف کند. | Medium | High | High | Packaging test؛ حفظ تصمیم تا Evidence | Business | OPEN |
| RSK-009 | رقیب AI تجربه سریع‌تر یا جذاب‌تری ارائه دهد. | High | Medium | High | رقابت بر Trust، Reviewed library، explainability و Persian fit | Strategy | OPEN |
| RSK-010 | تیله Hero باعث حواس‌پرتی یا افت Performance شود. | Medium | High | High | Prototype، performance budget، fallback و reduced-motion | Brand/UX | OPEN |
| RSK-011 | Motion فراگیر Accessibility را آسیب بزند. | Medium | High | High | Motion tokens، pause/reduce و accessibility QA | UX | OPEN |
| RSK-012 | داده کودک بیش از نیاز جمع‌آوری شود. | Medium | Critical | High | Data minimization، privacy review و عدم نیاز به عکس/نام خانوادگی | Privacy | OPEN |
| RSK-013 | Payment/Hosting ایران با عملیات بدون Terminal ناسازگار شود. | Medium | High | High | Research provider و GUI/automation deployment plan | Architecture/Ops | OPEN |
| RSK-014 | Metrics به Engagement داخل اپ منحرف شوند. | Medium | High | High | North Star=Play Starts و guardrail against session-time optimization | Product/Analytics | OPEN |
| RSK-015 | Desk evidence غیرایرانی به بازار ایران تعمیم داده شود. | High | High | High | Iran-specific evidence شرط Go/No-Go | Research | OPEN |

## Critical blockers for GO

- RSK-001 بدون Evidence مستقیم
- RSK-003 و RSK-004 بدون Pilot relevance/Safety
- RSK-005 بدون content throughput estimate
- RSK-007 بدون willingness-to-pay evidence برای Business viability
