# Prompt 020 — Editable Provisional Jigari Pricing

Status: FROZEN / EXECUTED
Date: 2026-09-13
Phase: 14 implementation slice

## Objective

نمایش قیمت‌های آزمایشی تومان برای سه پلن ثابت تیله جیگری و فراهم‌کردن ویرایش روزمره آن‌ها از Admin، بدون ساخت Checkout، Payment یا Entitlement تا بعد از MVP.

## Frozen scope

- پلن‌ها فقط ۳، ۶ و ۱۲ماهه و با Feature set یکسان باقی می‌مانند.
- قیمت‌های اولیه آزمایشی برابر ۳۹۰٬۰۰۰، ۶۹۰٬۰۰۰ و ۱٬۱۹۰٬۰۰۰ تومان هستند.
- Admin دارای `subscription.manage` می‌تواند عنوان، قیمت و نمایش عمومی هر پلن را ویرایش کند.
- ورودی Admin به تومان است؛ مقدار Canonical در `plans.price_minor` با Currency برابر `IRR` و تبدیل `1 toman = 10 IRR` ذخیره می‌شود.
- هر تغییر Plan باید Audit شود.
- صفحه عمومی باید قیمت را صریحاً آزمایشی و قابل تغییر معرفی کند.

## Excluded

Payment Provider، Checkout، Purchase creation، Callback، Refund، فعال‌سازی خودکار `JIGARI_ACTIVE`، Trial و پلن یک‌ماهه خارج Scope و موکول به بعد از MVP هستند.

## Acceptance evidence

- Guest قیمت‌های فعال را به تومان می‌بیند و هیچ Checkout action وجود ندارد.
- کاربر عادی و Content Editor به مدیریت قیمت دسترسی ندارند.
- Admin می‌تواند ارقام فارسی و جداکننده هزارگان وارد کند؛ Code و Duration قابل تغییر نیستند.
- غیرفعال‌کردن پلن فقط آن را از نمایش عمومی خارج می‌کند و هیچ Purchase، Payment Event یا Entitlement نمی‌سازد.
- تست‌های متمرکز و Regression روی MySQL 8.4.11 پاس می‌شوند.
