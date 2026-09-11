@php($digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹'])
<x-layouts.app title="تیله جیگری" description="عضویت تیله جیگری برای همراهی شخصی‌تر خانواده‌ها">
    <section class="jigari-page teelle-container" aria-labelledby="jigari-title">
        <header class="jigari-hero teelle-enter">
            <div class="jigari-orbit" aria-hidden="true">
                <span class="jigari-orbit__track jigari-orbit__track--one"><span class="jigari-orbit__planet jigari-orbit__planet--one"><img src="{{ asset('images/marbles/jigari-ruby-v1.webp') }}" alt="" width="768" height="768"></span></span>
                <span class="jigari-orbit__track jigari-orbit__track--two"><span class="jigari-orbit__planet jigari-orbit__planet--two"><img src="{{ asset('images/marbles/jigari-ruby-v1.webp') }}" alt="" width="768" height="768"></span></span>
                <span class="jigari-orbit__track jigari-orbit__track--three"><span class="jigari-orbit__planet jigari-orbit__planet--three"><img src="{{ asset('images/marbles/jigari-ruby-v1.webp') }}" alt="" width="768" height="768"></span></span>
            </div>
            <p class="match-kicker">همراهی که با خانواده بزرگ می‌شود</p>
            <h1 id="jigari-title">تیله جیگری</h1>
            <p>پروفایل‌های حداقلی کودک، برنامه‌ریزی بازی و کشف دقیق‌تر؛ بدون اینکه کیفیت پیشنهاد یا اطلاعات ایمنی پشت پرداخت پنهان شود</p>
            @if($active)
                <div class="jigari-hero__actions">
                    <x-ui.button href="{{ route('jigari.games.index') }}">جست‌وجوی بازی‌ها</x-ui.button>
                    <x-ui.button href="{{ route('account.children.index') }}" variant="secondary">مدیریت پروفایل کودکان</x-ui.button>
                </div>
            @elseif(auth()->check())
                <x-ui.button href="{{ route('account.show') }}" variant="secondary">بازگشت به حساب من</x-ui.button>
            @else
                <x-ui.button href="{{ route('register') }}" variant="secondary">ساخت حساب مراقب</x-ui.button>
            @endif
        </header>

        <section class="jigari-benefits" aria-labelledby="benefits-title">
            <div><p class="match-kicker">یک عضویت، یک تجربه</p><h2 id="benefits-title">در همه دوره‌ها امکانات یکسان است</h2></div>
            <ul>
                <li>پروفایل کودک با کمترین داده لازم</li>
                <li>Search و برنامه هفتگی در Sliceهای بعدی</li>
                <li>History کامل و تجربه چندکودکی پس از تکمیل Gateها</li>
                <li>پیشنهادهای اصلی و Safety همیشه برای همه رایگان می‌مانند</li>
            </ul>
        </section>

        <section aria-labelledby="plans-title">
            <div class="jigari-section-heading"><p class="match-kicker">دوره‌های مصوب</p><h2 id="plans-title">سه انتخاب، بدون تفاوت در امکانات</h2></div>
            <div class="jigari-plans">
                @forelse($plans as $plan)
                    <article class="jigari-plan">
                        <img class="jigari-plan__marble" src="{{ asset('images/marbles/jigari-ruby-v1.webp') }}" alt="" width="768" height="768" aria-hidden="true">
                        <h3>{{ strtr((string) $plan->duration_months, $digits) }} ماهه</h3>
                        <p>همه امکانات تیله جیگری</p>
                        <strong>قیمت و خرید هنوز فعال نشده</strong>
                    </article>
                @empty
                    <x-ui.state-message>پلن‌ها در حال آماده‌سازی‌اند و هنوز خریدی انجام نمی‌شود</x-ui.state-message>
                @endforelse
            </div>
        </section>

        <x-ui.state-message>تا تعیین قیمت و درگاه پرداخت، هیچ عضویت یا پرداختی از این صفحه ساخته نمی‌شود</x-ui.state-message>
    </section>
</x-layouts.app>
