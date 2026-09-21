<?php

return [
    // DEC-019: owner-approved presentation baseline; never inserted as play events.
    'heartbeat_baseline' => 121,

    // A dedicated production secret may be supplied; APP_KEY remains a secure fallback.
    'free_play_ip_hash_key' => env('FREE_PLAY_IP_HASH_KEY') ?: env('APP_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Deploy Version
    |--------------------------------------------------------------------------
    |
    | این مقدار پس از هر انتشار روی سرور تازه می‌شود (مثلاً از .env با
    | TEELLE_DEPLOY_VERSION یا مستقیم در همین فایل). مرورگر آن را با نسخه
    | قبلی مقایسه می‌کند و در صورت تفاوت، پیام «سایت رو به‌روز کردیم» را
    | نشان می‌دهد؛ بدون نیاز به پاک‌کردن دستی کش مرورگر یا دستگاه.
    |
    */

    'deploy_version' => env('TEELLE_DEPLOY_VERSION', '1'),

    /*
    |--------------------------------------------------------------------------
    | Session Max Age
    |--------------------------------------------------------------------------
    |
    | بازه استاندارد ریست نشست بر حسب دقیقه؛ پیش‌فرض هفت روز (10080 دقیقه).
    | پس از این بازه کاربر باید دوباره وارد شود، اما ثبت‌نام و همه داده‌های
    | حساب او باقی می‌ماند. مقدار صفر یعنی غیرفعال.
    |
    */

    'session_max_age_minutes' => (int) env('TEELLE_SESSION_MAX_AGE_MINUTES', 10080),
];
