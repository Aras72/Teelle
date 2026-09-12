<x-layouts.app title="خوش آمدید" description="شروع سبک و روشن با تیله">
    <section class="onboarding-page teelle-container" aria-labelledby="onboarding-title">
        <div class="onboarding-marble" aria-hidden="true"><img src="{{ asset('images/marbles/account-indigo-v1.webp') }}" alt="" width="768" height="768"></div>
        <article class="onboarding-card teelle-enter">
            <p class="match-kicker">خوش آمدید</p>
            <h1 id="onboarding-title">اینجا بازی اول است</h1>
            <p class="onboarding-lead">حساب شما آماده است؛ سه نکته کوتاه و بعد می‌رویم سراغ بازی</p>
            <ol class="onboarding-points">
                <li><strong>بازی مهمان آزاد می‌ماند</strong><span>برای پیدا کردن و شروع بازی نیازی به خرید نیست</span></li>
                <li><strong>خاطره‌ها پیش شما می‌مانند</strong><span>بازی‌های انجام‌شده و ذخیره‌ها را در حساب خود می‌بینید</span></li>
                <li><strong>اطلاعات کودک حداقلی است</strong><span>پروفایل اختیاری کودک فقط برای عضو جیگری است و نام خانوادگی، عکس یا جنسیت نمی‌خواهیم</span></li>
            </ol>
            <div class="onboarding-actions">
                <form method="post" action="{{ route('onboarding.store') }}">@csrf<input type="hidden" name="next" value="match"><x-ui.button type="submit">بزن بریم بازی</x-ui.button></form>
                <form method="post" action="{{ route('onboarding.store') }}">@csrf<input type="hidden" name="next" value="account"><button class="onboarding-skip" type="submit">فعلاً رد می‌کنم</button></form>
            </div>
        </article>
    </section>
</x-layouts.app>
