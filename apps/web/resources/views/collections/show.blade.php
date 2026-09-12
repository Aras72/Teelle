<x-layouts.app :title="$collection->title" :description="$collection->summary">
    <article class="collection-detail teelle-container" aria-labelledby="collection-title">
        <header class="collection-detail__heading teelle-enter">
            <p class="match-kicker">منتخب تیله</p><h1 id="collection-title">{{ $collection->title }}</h1><p>{{ $collection->summary }}</p>
            <a href="{{ route('collections.index') }}">بازگشت به مجموعه‌ها</a>
        </header>
        <div class="collection-games">
            @foreach($collection->games as $game)
                <article class="collection-game-card">
                    <img src="{{ route('collections.games.cover', [$collection, $game]) }}" width="800" height="600" alt="{{ $game->currentPublishedVersion->title }}" loading="lazy">
                    <div><h2>{{ $game->currentPublishedVersion->title }}</h2><p>{{ $game->currentPublishedVersion->summary }}</p><x-ui.button href="{{ route('match.show') }}" variant="secondary">بازی مناسب پیدا کن</x-ui.button></div>
                </article>
            @endforeach
        </div>
    </article>
</x-layouts.app>
