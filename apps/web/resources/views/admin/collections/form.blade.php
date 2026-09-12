<x-layouts.app :title="$collection ? 'ویرایش مجموعه' : 'مجموعه تازه'">
    <div class="admin-shell teelle-container">
        <header class="admin-heading"><div><p class="admin-kicker">Editorial</p><h1>{{ $collection ? $collection->title : 'پیش‌نویس مجموعه تازه' }}</h1></div><a href="{{ route('admin.content.collections.index') }}">بازگشت</a></header>
        @if(session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        @if($errors->any())<div class="admin-error" role="alert"><strong>عملیات انجام نشد</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <section class="admin-panel">
            @if(!$collection || $collection->status === 'draft')
            <form class="auth-form" method="post" action="{{ $collection ? route('admin.content.collections.update', $collection) : route('admin.content.collections.store') }}">@csrf @if($collection)@method('PUT')@endif
                <x-ui.field label="عنوان" name="title" value="{{ old('title', $collection?->title) }}" required :error="$errors->first('title')" />
                <x-ui.field label="Slug لاتین" name="slug" value="{{ old('slug', $collection?->slug) }}" dir="ltr" required :error="$errors->first('slug')" />
                <label for="summary">خلاصه</label><textarea class="teelle-input" id="summary" name="summary" rows="4" required>{{ old('summary', $collection?->summary) }}</textarea>
                <fieldset class="collection-game-picker"><legend>بازی‌ها به ترتیب شناسه</legend>
                    @forelse($games as $game)<label><input type="checkbox" name="game_ids[]" value="{{ $game->id }}" @checked(in_array($game->id, old('game_ids', $selected), true))><span>{{ $game->currentPublishedVersion?->title ?? $game->slug }}</span><small>{{ $game->status->value }}</small></label>@empty<p>هنوز بازی‌ای در سیستم نیست</p>@endforelse
                </fieldset>
                <x-ui.button type="submit">ذخیره پیش‌نویس</x-ui.button>
            </form>
            @endif
            @if($collection)
                <div class="collection-publish-actions">
                    @if($collection->status === 'draft' && auth()->user()->can('content.publish'))<form method="post" action="{{ route('admin.content.collections.publish', $collection) }}">@csrf<x-ui.button type="submit">انتشار پس از کنترل بازی‌ها</x-ui.button></form>@endif
                    @if($collection->status === 'published' && auth()->user()->can('content.publish'))<form class="auth-form" method="post" action="{{ route('admin.content.collections.unpublish', $collection) }}">@csrf<x-ui.field label="علت توقف انتشار" name="reason" required /><x-ui.button type="submit">توقف انتشار</x-ui.button></form>@endif
                </div>
            @endif
        </section>
    </div>
</x-layouts.app>
