<x-layouts.app title="مجموعه‌های بازی" description="مجموعه‌های منتخب بازی تیله برای موقعیت‌های واقعی خانواده">
    <section class="collections-page teelle-container" aria-labelledby="collections-title">
        <header class="collections-heading teelle-enter">
            <div class="collections-heading__marble" aria-hidden="true"><img src="{{ asset('images/marbles/match-violet-v1.webp') }}" width="768" height="768" alt=""></div>
            <p class="match-kicker">برای یک موقعیت واقعی</p>
            <h1 id="collections-title">یک بازی خوب برای همین حالا</h1>
            <p>چند مجموعه آماده برای وقت‌هایی که می‌خواهید بی‌معطلی بازی را شروع کنید.</p>
        </header>

        <div class="collection-list">
            @forelse($collections as $collection)
                <article class="collection-card">
                    <span class="collection-card__dot" aria-hidden="true"><img src="{{ asset('images/marbles/play-amber-v1.webp') }}" width="768" height="768" alt=""></span>
                    <div><h2><a href="{{ route('collections.show', $collection) }}">{{ $collection->title }}</a></h2><p>{{ $collection->summary }}</p></div>
                    <a class="collection-card__link" href="{{ route('collections.show', $collection) }}">دیدن بازی‌ها</a>
                </article>
            @empty
                <x-ui.state-message title="مجموعه تازه‌ای منتشر نشده">هر مجموعه‌ای که در این بخش منتشر شده، کاملاً بازبینی و از نظر ایمنی بررسی شده است.</x-ui.state-message>
            @endforelse
        </div>
    </section>
</x-layouts.app>
