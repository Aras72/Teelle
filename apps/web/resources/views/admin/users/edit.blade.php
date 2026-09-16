@php($digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹',','=>'٬'])
<x-layouts.app title="ویرایش کاربر" description="اصلاح مشخصات عمومی و دسترسی ادمین">
    <div class="admin-shell teelle-container admin-user-edit">
        <header class="admin-heading"><div><p class="admin-kicker">پشتیبانی کاربران</p><h1>{{ $user->name }}</h1><p>رمز عبور و سابقه خرید از این صفحه قابل تغییر نیست.</p></div><a href="{{ route('admin.content.users.index') }}">بازگشت</a></header>
        @if(session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        @if($errors->any())<div class="admin-error" role="alert"><strong>تغییر ذخیره نشد</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <section class="admin-panel" aria-labelledby="user-details-title">
            <h2 id="user-details-title">مشخصات عمومی</h2>
            <form class="admin-form admin-user-form" method="post" action="{{ route('admin.content.users.update', $user) }}">
                @csrf @method('PUT')
                <x-ui.field label="نام" name="name" value="{{ old('name', $user->name) }}" required :error="$errors->first('name')" />
                <x-ui.field label="ایمیل" name="email" type="email" dir="ltr" value="{{ old('email', $user->email) }}" required :error="$errors->first('email')" />
                <x-ui.field label="شماره موبایل با کد کشور" name="phone_e164" dir="ltr" placeholder="+989121234567" value="{{ old('phone_e164', $user->phone_e164) }}" :error="$errors->first('phone_e164')" />
                <x-ui.field label="دلیل تغییر یا شماره تیکت" name="reason" value="{{ old('reason') }}" required hint="برای سابقه ممیزی حداقل پنج نویسه بنویسید." :error="$errors->first('reason')" />
                <x-ui.state-message>اگر ایمیل یا موبایل تغییر کند، تأیید قبلی همان راه ارتباطی پاک می‌شود و کاربر باید دوباره آن را تأیید کند.</x-ui.state-message>
                <x-ui.button type="submit">ذخیره مشخصات</x-ui.button>
            </form>
        </section>

        <section class="admin-panel" aria-labelledby="membership-title">
            <h2 id="membership-title">پلن و عضویت</h2>
            <dl class="admin-user-facts">
                <div><dt>آخرین پلن خریداری‌شده</dt><dd>{{ $user->latestPurchase?->plan?->title ?? 'خریدی ثبت نشده' }}</dd></div>
                <div><dt>مبلغ ثبت‌شده</dt><dd>{{ $user->latestPurchase ? strtr(number_format(intdiv((int) $user->latestPurchase->amount_minor, 10)), $digits).' تومان' : 'ثبت نشده' }}</dd></div>
                <div><dt>وضعیت عضویت جیگری</dt><dd>{{ $user->latestEntitlement ? match($user->latestEntitlement->status->value) { 'active' => 'فعال', 'pending' => 'در انتظار', 'expired' => 'پایان‌یافته', 'cancelled' => 'لغوشده', 'refunded' => 'بازپرداخت‌شده', 'revoked' => 'برداشته‌شده' } : 'عضویتی ثبت نشده' }}</dd></div>
                <div><dt>نقش‌های فعلی</dt><dd>{{ $user->roles->pluck('title')->implode('، ') ?: 'عضو' }}</dd></div>
            </dl>
        </section>

        @can('roles.manage')
            <section class="admin-panel" aria-labelledby="role-title">
                <h2 id="role-title">دسترسی ادمین</h2>
                <p>افزودن و ویرایش بازی‌ها و مجله، دسترسی پایه ادمین است. سایر بخش‌ها را برای هر ادمین جداگانه انتخاب کنید. تأیید و انتشار نهایی فقط با مدیر است.</p>
                <form class="admin-form admin-role-form" method="post" action="{{ route('admin.content.users.role.update', $user) }}">
                    @csrf @method('PUT')
                    <label class="teelle-check"><input type="checkbox" name="support_admin" value="1" @checked(old('support_admin', $user->roles->contains('code', 'support_admin')))><span>دسترسی ادمین فعال باشد</span></label>
                    <fieldset><legend>بخش‌های در دسترس ادمین</legend><div class="admin-choice-grid">
                        @foreach($delegablePermissions as $permission)
                            @php($isBase = in_array($permission->code, ['content.edit', 'articles.edit'], true))
                            <label class="teelle-check">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->code }}" @checked($isBase || in_array($permission->code, old('permissions', $selectedPermissionCodes), true)) @disabled($isBase)>
                                @if($isBase)<input type="hidden" name="permissions[]" value="{{ $permission->code }}">@endif
                                <span>{{ $permission->title }}{{ $isBase ? ' — دسترسی پایه' : '' }}</span>
                            </label>
                        @endforeach
                    </div></fieldset>
                    <x-ui.field label="دلیل تغییر یا شماره تیکت" name="reason" value="{{ old('reason') }}" required :error="$errors->first('reason')" />
                    <x-ui.button type="submit">ذخیره دسترسی</x-ui.button>
                </form>
            </section>
        @endcan
    </div>
</x-layouts.app>
