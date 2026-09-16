<x-layouts.app title="مجله تیله" description="قصه‌ها، راهنماها و ایده‌های واقعی برای وقت کنار کودک">
    <main class="magazine-page teelle-container">
        <header class="magazine-hero teelle-enter">
            <p class="page-kicker">مجله تیله</p>
            <h1>{{ $category ? $category->title : 'چیزهایی برای خواندن، ساختن و باهم‌بودن' }}</h1>
            <p>{{ $category?->description ?: 'قصه‌های شب، راهنمای بازی و ایده‌هایی که وقت واقعی کنار کودک را شیرین‌تر می‌کنند.' }}</p>
        </header>
        <nav class="magazine-categories" aria-label="موضوع‌های مجله">
            <a href="{{ route('magazine.index') }}" @class(['is-active' => ! $category])>همه</a>
            @foreach($categories as $item)<a href="{{ route('magazine.index', ['category' => $item->slug]) }}" @class(['is-active' => $category?->is($item)])>{{ $item->title }}</a>@endforeach
        </nav>
        <section class="magazine-grid" aria-label="مطالب مجله">
            @forelse($articles as $article)
                <article class="magazine-card teelle-enter">
                    @if($article->cover_path)<a href="{{ route('magazine.show', $article) }}"><img src="{{ route('magazine.cover', $article) }}" alt="{{ $article->cover_alt }}"></a>@endif
                    <div><p class="page-kicker">{{ $article->categories->pluck('title')->implode('، ') }}</p><h2><a href="{{ route('magazine.show', $article) }}">{{ $article->title }}</a></h2><p>{{ $article->excerpt }}</p><a class="magazine-read" href="{{ route('magazine.show', $article) }}">ادامه مطلب</a></div>
                </article>
            @empty
                <x-ui.state-message>هنوز مطلبی در این موضوع منتشر نشده است.</x-ui.state-message>
            @endforelse
        </section>
        {{ $articles->links() }}
    </main>
</x-layouts.app>
