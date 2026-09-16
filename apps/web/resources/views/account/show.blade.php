@php($digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹'])
<x-layouts.app title="حساب من" description="خاطره‌ها، بازی‌های ذخیره‌شده و تنظیمات حساب تیله">
    <section class="account-page teelle-container" aria-labelledby="account-title">
        <header class="account-heading teelle-enter">
            <div class="account-marble" aria-hidden="true"><img src="{{ asset('images/marbles/account-indigo-v1.webp') }}" alt="" width="768" height="768"></div>
            <div>
                <p class="match-kicker">خانه شما در تیله</p><h1 id="account-title">سلام {{ auth()->user()->name }}</h1><p>بازی‌های انجام‌شده و انتخاب‌های دوست‌داشتنی شما اینجا می‌مانند.</p>
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
                    <p class="account-empty">هنوز بازی‌ای ذخیره نکرده‌اید؛ بعد از انجام یک بازی می‌توانید آن را برای بعد نگه دارید.</p>
                @else
                    <div class="saved-grid">
                        @foreach($saved as $row)
                            <article class="saved-card">
                                @if($row['available'])
                                    <img src="{{ route('account.saved.cover', $row['item']->game) }}" alt="{{ $row['item']->game->currentPublishedVersion->title }}" loading="lazy">
                                    <div><h3>{{ $row['item']->game->currentPublishedVersion->title }}</h3><p>آماده برای یک وقت با هم بودن.</p></div>
                                @else
                                    <div class="saved-card__unavailable" aria-hidden="true"><span></span></div>
                                    <div><h3>این بازی فعلاً در دسترس نیست</h3><p>تا بازبینی دوباره، جزئیات آن نمایش داده نمی‌شود.</p></div>
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
                    <p class="account-empty">اولین بازی شما هنوز منتظر شروع است.</p>
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
                    <x-ui.field label="نام شما" name="name" value="{{ old('name', auth()->user()->name) }}" autocomplete="name" hint="مثلاً: داییِ ارغوان، مامانِ کوهیار" required :error="$errors->first('name')" />
                    <x-ui.field label="ایمیل تأییدشده" name="account_email" type="email" value="{{ auth()->user()->email }}" disabled />
                    <input type="hidden" name="timezone" value="Asia/Tehran">
                    <x-ui.button type="submit">ذخیره تنظیمات</x-ui.button>
                </form>
            </section>

            <section class="account-panel" aria-labelledby="jigari-account-title">
                <div class="account-panel__heading"><div><p class="match-kicker">تیله جیگری</p><h2 id="jigari-account-title">پروفایل کودک</h2></div></div>
                @if($jigariActive)
                    <p class="account-empty">عضویت فعال است و {{ strtr((string) $childCount, $digits) }} پروفایل فعال دارید.</p>
                    <x-ui.button href="{{ route('account.children.index') }}">مدیریت پروفایل‌ها</x-ui.button>
                @else
                    <p class="account-empty">پروفایل کودک فقط با عضویت فعال جیگری در دسترس است.</p>
                    <x-ui.button href="{{ route('jigari.show') }}" variant="secondary">آشنایی با تیله جیگری</x-ui.button>
                @endif
            </section>

            <section class="account-panel account-panel--wide support-panel" aria-labelledby="support-title">
                <div class="account-panel__heading"><div><p class="match-kicker">کنار شماییم</p><h2 id="support-title">پشتیبانی و تیکت‌ها</h2></div></div>
                <p class="account-empty">هر سؤال، مشکل یا پیشنهادی دارید برای ما بنویسید. پاسخ تیکت را همین‌جا می‌بینید.</p>
                <form method="post" action="{{ route('account.tickets.store') }}" class="support-ticket-form">
                    @csrf
                    <x-ui.field label="موضوع" name="subject" value="{{ old('subject') }}" maxlength="160" placeholder="مثلاً مشکل ورود، پیشنهاد بازی یا سؤال درباره عضویت" required :error="$errors->first('subject')" />
                    <label for="ticket-body">پیام شما</label>
                    <textarea id="ticket-body" name="body" rows="5" maxlength="5000" placeholder="هرچقدر لازم است توضیح بدهید" required>{{ old('body') }}</textarea>
                    @error('body')<p class="field-error">{{ $message }}</p>@enderror
                    <x-ui.button type="submit">ارسال تیکت</x-ui.button>
                </form>

                @if($tickets->isNotEmpty())
                    @php($ticketStatuses = ['open'=>'باز','in_progress'=>'در حال پیگیری','resolved'=>'پاسخ داده شده','closed'=>'بسته'])
                    <div class="support-ticket-history" aria-labelledby="support-history-title">
                        <h3 id="support-history-title">تیکت‌های من</h3>
                        @foreach($tickets as $ticket)
                            <article class="support-ticket-item">
                                <header><h4>{{ $ticket->subject }}</h4><span class="ticket-status ticket-status--{{ $ticket->status }}">{{ $ticketStatuses[$ticket->status] }}</span></header>
                                <p>{{ $ticket->body }}</p>
                                @if($ticket->admin_reply)<div class="support-ticket-reply"><strong>پاسخ تیله</strong><p>{{ $ticket->admin_reply }}</p></div>@endif
                                <time datetime="{{ $ticket->created_at->toIso8601String() }}">{{ strtr($ticket->created_at->format('Y/m/d H:i'), $digits) }}</time>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="account-panel account-panel--wide privacy-panel" aria-labelledby="privacy-title">
                <div class="account-panel__heading"><div><p class="match-kicker">کنترل داده‌ها</p><h2 id="privacy-title">حریم خصوصی حساب</h2></div></div>
                <p class="account-empty">این حساب برای بزرگسال همراه کودک است. فایل خروجی فقط داده‌های مربوط به همین حساب را دارد و رمز عبور، نشست‌ها و شناسه‌های داخلی در آن قرار نمی‌گیرند.</p>

                <div class="privacy-actions">
                    <form method="post" action="{{ route('account.privacy.export') }}" class="privacy-form">
                        @csrf
                        <h3>دریافت نسخه داده‌ها</h3>
                        <p>یک فایل JSON خوانا شامل مشخصات حساب، پروفایل‌های کودک، سابقه Match و بازی، ذخیره‌ها و وضعیت عضویت دریافت می‌کنید.</p>
                        <x-ui.field label="رمز فعلی برای تأیید" name="export_password" type="password" autocomplete="current-password" required :error="$errors->first('export_password')" />
                        <x-ui.button type="submit" variant="secondary">دریافت فایل داده‌های من</x-ui.button>
                    </form>

                    <div class="privacy-form privacy-form--danger">
                        <h3>حذف حساب</h3>
                        @if($pendingDeletion)
                            <p class="privacy-warning">درخواست حذف فعال است و برای {{ strtr($pendingDeletion->scheduled_for->format('Y/m/d'), $digits) }} برنامه‌ریزی شده است. تا آن روز می‌توانید آن را لغو کنید.</p>
                            <form method="post" action="{{ route('account.privacy.deletion.destroy') }}">
                                @csrf @method('DELETE')
                                <x-ui.button type="submit" variant="secondary">فعلاً حسابم بماند</x-ui.button>
                            </form>
                        @else
                            <p>پس از ثبت درخواست حذف حساب، ۳ روز برای درخواست بازگردانی از پشتیبانی فرصت دارید.</p>
                            <form method="post" action="{{ route('account.privacy.deletion.store') }}" class="privacy-form__request">
                                @csrf
                                <x-ui.field label="رمز فعلی برای تأیید حذف" name="deletion_password" type="password" autocomplete="current-password" required :error="$errors->first('deletion_password')" />
                                <x-ui.button type="submit">درخواست حذف حساب</x-ui.button>
                            </form>
                        @endif
                    </div>
                </div>

                @if($privacyRequests->isNotEmpty())
                    <div aria-labelledby="privacy-history-title">
                        <h3 id="privacy-history-title">سابقه درخواست‌ها</h3>
                        <ol class="privacy-request-list">
                            @foreach($privacyRequests as $privacyRequest)
                                <li>
                                    <strong>{{ $privacyRequest->request_type === 'export' ? 'خروجی داده‌ها' : 'حذف حساب' }}</strong>
                                    <span>{{ match($privacyRequest->status) { 'completed' => 'تکمیل‌شده', 'cancelled' => 'لغوشده', default => 'در انتظار' } }} · {{ strtr($privacyRequest->requested_at->format('Y/m/d H:i'), $digits) }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif
            </section>
        </div>
    </section>
</x-layouts.app>
