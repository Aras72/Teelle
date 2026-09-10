# Unit Tests

Date: 2026-09-10

| مجموعه | نتیجه فعلی | Evidence |
|---|---:|---|
| JavaScript | PASS | 8/8 test |
| Design System PHP | PASS | 5 test / 31 assertion |
| Homepage PHP | PASS | 5 test / 34 assertion |
| Foundation و Security headers | PASS | 6 test / 36 assertion |
| PHP Unit مستقل | PASS | 1 test |

تست جدید Responsive تضمین می‌کند Container قابلیت shrink داشته باشد و شعار Heartbeat فقط از عرض Desktop به بعد `nowrap` شود. تست‌های `marble.js` مربوط به Spike سه‌بعدی نگه داشته شده‌اند، اما فعال‌بودن آن مدل مصنوعی را ثابت نمی‌کنند؛ اجرای فعلی Hero ویدئویی و fallback آن در HomepageTest پوشش داده می‌شود.
