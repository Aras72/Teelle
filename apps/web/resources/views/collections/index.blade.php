<x-layouts.app title="مجموعه‌های بازی" description="مجموعه‌های منتخب بازی تیله برای موقعیت‌های واقعی خانواده">
    <section class="collections-page teelle-container" aria-labelledby="collections-title">
        <header class="collections-heading teelle-enter">
            <div class="collections-heading__marble" aria-hidden="true"><img src="{{ asset('images/marbles/match-violet-v1.webp') }}" width="768" height="768" alt=""></div>
            <p class="match-kicker">برای یک موقعیت واقعی</p>
            <h1 id="collections-title">مجموعه‌های بازی</h1>
            <p>انتخاب‌های سردبیری‌شده برای وقتی که می‌خواهید زودتر به بازی برسید</p>
        </header>

        <div class="collection-list">
            @forelse($collections as $collection)
                <article class="collection-card">
                    <span class="collection-card__dot" aria-hidden="true"><img src="{{ asset('images/marbles/play-amber-v1.webp') }}" width="768" height="768" alt=""></span>
                    <div><h2><a href="{{ route('collections.show', $collection) }}">{{ $collection->title }}</a></h2><p>{{ $collection->summary }}</p></div>
                    <a class="collection-card__link" href="{{ route('collections.show', $collection) }}">دیدن بازی‌ها</a>
                </article>
            @empty
                <x-ui.state-message title="مجموعه تازه‌ای منتشر نشده">فقط مجموعه‌ای نشان داده می‌شود که بازی‌هایش منتشر، بازبینی‌شده و ایمن باشند</x-ui.state-message>
            @endforelse
        </div>
    </section>
</x-layouts.app>
