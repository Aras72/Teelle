<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MagazineController extends Controller
{
    public function index(Request $request): View
    {
        $category = filled($request->query('category'))
            ? ArticleCategory::query()->where('slug', $request->query('category'))->where('is_active', true)->firstOrFail()
            : null;
        $articles = Article::query()->with('categories')
            ->where('status', 'published')
            ->when($category, fn ($query) => $query->whereHas('categories', fn ($nested) => $nested->whereKey($category->id)))
            ->latest('published_at')->paginate(12)->withQueryString();
        $categories = ArticleCategory::query()->where('is_active', true)->orderBy('sort_order')->orderBy('title')->get();

        return view('magazine.index', compact('articles', 'categories', 'category'));
    }

    public function show(Article $article): View
    {
        abort_unless($article->status === 'published', 404);
        $article->load('categories');
        $bodyHtml = Str::markdown($article->body_markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return view('magazine.show', compact('article', 'bodyHtml'));
    }

    public function cover(Article $article): StreamedResponse
    {
        abort_unless($article->status === 'published' && $article->cover_disk && $article->cover_path, 404);

        return Storage::disk($article->cover_disk)->response($article->cover_path, null, [
            'Content-Type' => $article->cover_mime,
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
