<x-layouts.app title="{{ $article->seo_title ?: $article->title }}" description="{{ $article->seo_description ?: $article->excerpt }}">
    <main class="magazine-article teelle-container">
        <a class="magazine-back" href="{{ route('magazine.index') }}">بازگشت به مجله</a>
        <article>
            <header><p class="page-kicker">{{ $article->categories->pluck('title')->implode('، ') }}</p><h1>{{ $article->title }}</h1><p class="magazine-lead">{{ $article->excerpt }}</p></header>
            @if($article->cover_path)<img class="magazine-cover" src="{{ route('magazine.cover', $article) }}" alt="{{ $article->cover_alt }}">@endif
            <div class="magazine-body">{!! $bodyHtml !!}</div>
        </article>
    </main>
</x-layouts.app>
