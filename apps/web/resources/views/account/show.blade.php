@php($digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹'])
<x-layouts.app title="حساب من" description="خاطره‌ها، بازی‌های ذخیره‌شده و تنظیمات حساب تیله">
    <section class="account-page teelle-container" aria-labelledby="account-title">
        <header class="account-heading teelle-enter">
            <div class="account-marble" aria-hidden="true"><img src="{{ asset('images/teelle-hero-marble-poster-v1.png') }}" alt="" width="1536" height="1024"></div>
            <div>
                <p class="match-kicker">خانه شما در تیله</p><h1 id="account-title">سلام {{ auth()->user()->name }}</h1><p>بازی‌های انجام‌شده و انتخاب‌های دوست‌داشتنی شما اینجا می‌مانند</p>
                <div class="account-heading__actions">
                    <x-ui.button href="{{ route('match.show') }}">شروع یک بازی</x-ui.button>
                    <form method="post" action="{{ route('logout') }}">@csrf<x-ui.button type="submit" variant="secondary">خروج از حساب</x-ui.button></form>
                </div>
            </div>
        </header>
        @if(session('status'))<x-ui.state-message>{{ session('status') }}</x-ui.state-message>@endif

        <div class="account-grid">
            <section class="account-panel account-panel--wide" aria-labelledby="saved-title">
                <div class="account-panel__heading"><div><p class="match-kicker">برای بعد</p><h2 id="saved-title">بازی‌های ذخیره‌شده</h2></div><span>{{ strtr((string) $saved->count(), $digits) }} بازی</span></div>
                @if($saved->isEmpty())
                    <p class="account-empty">هنوز بازی‌ای ذخیره نکرده‌اید؛ بعد از انجام یک بازی می‌توانید آن را برای بعد نگه دارید</p>
                @else
                    <div class="saved-grid">
                        @foreach($saved as $row)
                            <article class="saved-card">
                                @if($row['available'])
                                    <img src="{{ route('account.saved.cover', $row['item']->game) }}" alt="{{ $row['item']->game->currentPublishedVersion->title }}" loading="lazy">
                                    <div><h3>{{ $row['item']->game->currentPublishedVersion->title }}</h3><p>آماده برای یک وقت با هم بودن</p></div>
                                @else
                                    <div class="saved-card__unavailable" aria-hidden="true"><span></span></div>
                                    <div><h3>این بازی فعلاً در دسترس نیست</h3><p>تا بازبینی دوباره، جزئیات آن نمایش داده نمی‌شود</p></div>
                                @endif
                                <form method="post" action="{{ route('account.saved.destroy', $row['item']->game) }}">@csrf @method('DELETE')<button type="submit">برداشتن از ذخیره‌ها</button></form>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="account-panel" aria-labelledby="history-title">
                <div class="account-panel__heading"><div><p class="match-kicker">خاطره‌های بازی</p><h2 id="history-title">سابقه</h2></div></div>
                @if($history->isEmpty())
                    <p class="account-empty">اولین بازی شما هنوز منتظر شروع است</p>
                @else
                    <ol class="history-list">
                        @foreach($history as $play)
                            <li><span class="history-dot" aria-hidden="true"></span><div><strong>{{ $play->result->gameVersion->title }}</strong><span>{{ $play->state->value === 'completed' ? 'انجام شد' : 'شروع شد' }} در {{ strtr($play->started_at?->format('Y/m/d') ?? $play->created_at->format('Y/m/d'), $digits) }}</span></div></li>
                        @endforeach
                    </ol>
                @endif
            </section>

            <section class="account-panel" aria-labelledby="settings-title">
                <div class="account-panel__heading"><div><p class="match-kicker">تنظیمات پایه</p><h2 id="settings-title">مشخصات حساب</h2></div></div>
                <form method="post" action="{{ route('account.update') }}" class="auth-form">
                    @csrf @method('PUT')
                    <x-ui.field label="نام شما" name="name" value="{{ old('name', auth()->user()->name) }}" autocomplete="name" required :error="$errors->first('name')" />
                    <x-ui.field label="ایمیل تأییدشده" name="account_email" type="email" value="{{ auth()->user()->email }}" disabled />
                    <input type="hidden" name="timezone" value="Asia/Tehran">
                    <x-ui.button type="submit">ذخیره تنظیمات</x-ui.button>
                </form>
            </section>

            <section class="account-panel" aria-labelledby="jigari-account-title">
                <div class="account-panel__heading"><div><p class="match-kicker">تیله جیگری</p><h2 id="jigari-account-title">پروفایل کودکان</h2></div></div>
                @if($jigariActive)
                    <p class="account-empty">عضویت فعال است و {{ strtr((string) $childCount, $digits) }} پروفایل فعال دارید</p>
                    <x-ui.button href="{{ route('account.children.index') }}">مدیریت پروفایل‌ها</x-ui.button>
                @else
                    <p class="account-empty">پروفایل کودک فقط با عضویت فعال جیگری در دسترس است؛ اطلاعات قبلی پس از پایان عضویت حذف نمی‌شوند</p>
                    <x-ui.button href="{{ route('jigari.show') }}" variant="secondary">آشنایی با تیله جیگری</x-ui.button>
                @endif
            </section>
        </div>
    </section>
</x-layouts.app>
