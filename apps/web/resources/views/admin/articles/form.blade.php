<x-layouts.app title="{{ $article->exists ? 'ویرایش مطلب' : 'مطلب تازه' }}" description="فرم کامل مجله تیله">
    <div class="admin-shell teelle-container">
        <header class="admin-heading"><div><p class="admin-kicker">مجله تیله</p><h1>{{ $article->exists ? $article->title : 'مطلب تازه' }}</h1></div><a href="{{ route('admin.content.articles.index') }}">بازگشت</a></header>
        @if(session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        @if($errors->any())<div class="admin-error" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form class="admin-form admin-panel" method="post" enctype="multipart/form-data" action="{{ $article->exists ? route('admin.content.articles.update', $article) : route('admin.content.articles.store') }}">
            @csrf @if($article->exists) @method('PUT') @endif
            <div class="admin-form-grid">
                <label>عنوان<input class="teelle-input" name="title" value="{{ old('title', $article->title) }}" required maxlength="220"></label>
                <label>نام کوتاه انگلیسی برای نشانی<input class="teelle-input" dir="ltr" name="slug" value="{{ old('slug', $article->slug) }}" required maxlength="160"></label>
                <label class="admin-field-wide">خلاصه<textarea class="teelle-input" name="excerpt" rows="3" required>{{ old('excerpt', $article->excerpt) }}</textarea></label>
                <label class="admin-field-wide">متن کامل<textarea class="teelle-input" name="body_markdown" rows="18" required>{{ old('body_markdown', $article->body_markdown) }}</textarea><small>برای تیتر داخل متن از ## و برای فهرست از - استفاده کنید.</small></label>
            </div>
            <fieldset><legend>موضوع‌ها</legend><div class="admin-choice-grid">@forelse($categories as $category)<label class="teelle-check"><input type="checkbox" name="categories[]" value="{{ $category->id }}" @checked(in_array($category->id, old('categories', $article->categories->pluck('id')->all()), true))><span>{{ $category->title }}</span></label>@empty<p>ابتدا یک دسته‌بندی بسازید.</p>@endforelse</div></fieldset>
            <fieldset><legend>تصویر اصلی</legend><div class="admin-form-grid"><label>تصویر<input class="teelle-input" type="file" name="cover" accept="image/jpeg,image/png,image/webp"></label><label>توضیح تصویر<input class="teelle-input" name="cover_alt" value="{{ old('cover_alt', $article->cover_alt) }}" maxlength="500"></label></div>@if($article->cover_path)<img class="admin-article-cover" src="{{ route('admin.content.articles.cover', $article) }}" alt="{{ $article->cover_alt }}">@endif</fieldset>
            <fieldset><legend>نمایش در گوگل</legend><div class="admin-form-grid"><label>عنوان در نتیجه جست‌وجو<input class="teelle-input" name="seo_title" value="{{ old('seo_title', $article->seo_title) }}" maxlength="220"><small>خالی بگذارید تا همان عنوان مطلب در گوگل نشان داده شود</small></label><label class="admin-field-wide">توضیح کوتاه زیر نتیجه جست‌وجو<textarea class="teelle-input" name="seo_description" rows="3" maxlength="320">{{ old('seo_description', $article->seo_description) }}</textarea><small>یک یا دو جمله خلاصه که در گوگل زیر عنوان مطلب نمایش داده می‌شود</small></label></div></fieldset>
            <x-ui.button type="submit">ذخیره پیش‌نویس</x-ui.button>
        </form>
    </div>
</x-layouts.app>
