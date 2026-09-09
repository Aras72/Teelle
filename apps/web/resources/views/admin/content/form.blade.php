@php($base = $version ?? $source)
<x-layouts.app title="{{ $version ? 'ویرایش پیش‌نویس' : ($source ? 'نسخه تازه' : 'پیش‌نویس تازه') }}" description="ویرایش ساختاریافته محتوای بازی">
    <div class="admin-shell teelle-container">
        <header class="admin-heading"><div><p class="admin-kicker">محتوای بازی</p><h1>{{ $version ? 'ویرایش نسخه '.$version->version_no : 'ساخت پیش‌نویس' }}</h1></div><a href="{{ route('admin.content.index') }}">بازگشت به فهرست</a></header>
        @if (session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        @if ($errors->any())<div class="admin-error" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <form class="admin-form admin-panel" method="post" action="{{ $version ? route('admin.content.update', $version) : ($source ? route('admin.content.revision.store', $game) : route('admin.content.store')) }}">
            @csrf @if($version) @method('PUT') @endif
            @unless($version)<label>شناسه انگلیسی<input class="teelle-input" dir="ltr" name="slug" value="{{ old('slug') }}" required maxlength="120"></label>@endunless
            <label>عنوان<input class="teelle-input" name="title" value="{{ old('title', $base?->title) }}" required maxlength="180"></label>
            <label>خلاصه<textarea class="teelle-input" name="summary" rows="3" required>{{ old('summary', $base?->summary) }}</textarea></label>
            <label>مراحل بازی، هر مرحله در یک خط<textarea class="teelle-input" name="instructions_text" rows="7" required>{{ old('instructions_text', $base ? implode("\n", $base->instructions) : '') }}</textarea></label>
            <label>نکات ایمنی<textarea class="teelle-input" name="safety_copy" rows="4" required>{{ old('safety_copy', $base?->safety_copy) }}</textarea></label>
            <label>موارد منع، هر مورد در یک خط<textarea class="teelle-input" name="contraindications_text" rows="3">{{ old('contraindications_text', $base ? implode("\n", $base->contraindications ?? []) : '') }}</textarea></label>
            <label>سطح نظارت<select class="teelle-input" name="supervision_level" required>@foreach(['within_reach'=>'در دسترس مستقیم','same_room'=>'در همان اتاق','check_in'=>'بررسی دوره‌ای'] as $value=>$label)<option value="{{ $value }}" @selected(old('supervision_level', $base?->supervision_level) === $value)>{{ $label }}</option>@endforeach</select></label>
            <x-ui.button type="submit">ذخیره پیش‌نویس</x-ui.button>
        </form>

        @if($version)
        <section class="admin-panel"><h2>Metadata کامل بازی</h2><p>تمام Factها، Taxonomyها، منبع و Safety این نسخه در JSON زیر ثبت می‌شود. مقدارها قبل از ذخیره با Vocabulary تیله اعتبارسنجی می‌شوند</p>
            <form class="admin-form" method="post" action="{{ route('admin.content.structured-metadata', $version) }}">@csrf
                <label>Metadata JSON<textarea class="teelle-input admin-code" name="metadata_json" rows="22" dir="ltr" required>{{ old('metadata_json', $metadataJson) }}</textarea></label>
                <x-ui.button type="submit" variant="secondary">اعتبارسنجی و ذخیره Metadata</x-ui.button>
            </form>
        </section>
        <section class="admin-panel"><h2>رسانه قرنطینه‌ای</h2><p>تصویر تا تأیید یک بازبین دیگر خصوصی می‌ماند</p>
            <form class="admin-form" method="post" enctype="multipart/form-data" action="{{ route('admin.content.media.store', $version) }}">@csrf
                <label>تصویر<input class="teelle-input" type="file" name="image" accept="image/jpeg,image/png,image/webp" required></label>
                <label>متن جایگزین<input class="teelle-input" name="alt_text" required maxlength="500"></label>
                <label>نقش<select class="teelle-input" name="role"><option value="cover">کاور</option><option value="detail">جزئیات</option><option value="step">مرحله</option></select></label>
                <input type="hidden" name="sort_order" value="0"><input type="hidden" name="crop_json" value='{"x":0.5,"y":0.5,"ratio":"4:3"}'>
                <x-ui.button type="submit" variant="secondary">بارگذاری در قرنطینه</x-ui.button>
            </form>
            <ul class="admin-media">@forelse($media as $asset)<li><img src="{{ route('admin.content.media.show', $asset) }}" alt="{{ $asset->alt_text }}"><span>{{ $asset->original_name }} - {{ $asset->status }}</span>@can('content.review')<form method="post" action="{{ route('admin.content.media.review', $asset) }}">@csrf<button>تأیید رسانه</button></form>@endcan</li>@empty<li>تصویری متصل نشده است</li>@endforelse</ul>
        </section>
        <section class="admin-panel"><h2>پیش‌نمایش متن در دو تم</h2><div class="admin-theme-preview"><article class="is-light"><strong>{{ $version->title }}</strong><p>{{ $version->summary }}</p><small>{{ $version->safety_copy }}</small></article><article class="is-dark"><strong>{{ $version->title }}</strong><p>{{ $version->summary }}</p><small>{{ $version->safety_copy }}</small></article></div></section>
        @endif
    </div>
</x-layouts.app>
