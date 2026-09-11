@php($digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹'])
<x-layouts.app title="پروفایل کودکان" description="مدیریت حداقلی پروفایل کودک در تیله جیگری">
    <section class="children-page teelle-container" aria-labelledby="children-title">
        <header class="children-heading teelle-enter">
            <div><p class="match-kicker">تیله جیگری</p><h1 id="children-title">پروفایل کودکان</h1><p>فقط اطلاعاتی را نگه می‌داریم که برای تجربه بازی لازم است</p></div>
            <x-ui.button href="{{ route('account.children.create') }}">افزودن کودک</x-ui.button>
        </header>
        @if(session('status'))<x-ui.state-message>{{ session('status') }}</x-ui.state-message>@endif

        @if($children->isEmpty())
            <div class="children-empty">
                <img class="children-empty__marble" src="{{ asset('images/marbles/account-indigo-v1.webp') }}" alt="" width="768" height="768" aria-hidden="true">
                <h2>هنوز پروفایلی اینجا نیست</h2>
                <p>برای شروع فقط ماه تولد و یک نام کوچک یا لقب اختیاری کافی است</p>
                <x-ui.button href="{{ route('account.children.create') }}">ساخت اولین پروفایل</x-ui.button>
            </div>
        @else
            <div class="children-grid">
                @foreach($children as $child)
                    <article class="child-card {{ $child->status === 'archived' ? 'is-archived' : '' }}">
                        <img class="child-card__marble" src="{{ asset('images/marbles/account-indigo-v1.webp') }}" alt="" width="768" height="768" aria-hidden="true">
                        <div><p class="match-kicker">{{ $child->status === 'active' ? 'فعال' : 'بایگانی‌شده' }}</p><h2>{{ $child->nickname ?: 'کودک من' }}</h2><p>ماه تولد: <bdi>{{ strtr($child->birth_month, $digits) }}</bdi></p></div>
                        @if($child->status === 'active')
                            <div class="child-card__actions">
                                <x-ui.button href="{{ route('account.children.edit', $child->public_id) }}" variant="secondary">ویرایش</x-ui.button>
                                <form method="post" action="{{ route('account.children.archive', $child->public_id) }}">@csrf<x-ui.button type="submit" variant="quiet">بایگانی</x-ui.button></form>
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</x-layouts.app>
