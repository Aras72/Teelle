<?php

namespace App\Http\Controllers\Admin;

use App\Content\AuditWriter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveArticleRequest;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->can('articles.edit') || $request->user()->can('articles.publish'), 403);

        return view('admin.articles.index', [
            'articles' => Article::query()->with(['categories', 'author'])->latest()->paginate(25),
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->can('articles.edit'), 403);

        return view('admin.articles.form', ['article' => new Article, 'categories' => $this->categories()]);
    }

    public function store(SaveArticleRequest $request, AuditWriter $audit): RedirectResponse
    {
        $article = $this->save($request, new Article, $audit);

        return redirect()->route('admin.content.articles.edit', $article)->with('status', 'پیش‌نویس مطلب ساخته شد');
    }

    public function edit(Request $request, Article $article): View
    {
        abort_unless($request->user()->can('articles.edit'), 403);
        abort_if($article->status === 'published', 409, 'برای ویرایش، مدیر باید ابتدا انتشار مطلب را متوقف کند');

        return view('admin.articles.form', ['article' => $article->load('categories'), 'categories' => $this->categories()]);
    }

    public function update(SaveArticleRequest $request, Article $article, AuditWriter $audit): RedirectResponse
    {
        abort_if($article->status === 'published', 409, 'برای ویرایش، ابتدا انتشار را متوقف کنید');
        $this->save($request, $article, $audit);

        return back()->with('status', 'مطلب ذخیره شد');
    }

    public function submit(Request $request, Article $article, AuditWriter $audit): RedirectResponse
    {
        abort_unless($request->user()->can('articles.edit'), 403);
        abort_unless($article->status === 'draft', 409);
        $before = ['status' => $article->status];
        $article->update(['status' => 'in_review', 'submitted_by' => $request->user()->id, 'submitted_at' => now()]);
        $audit->write($request->user(), 'magazine.article.submitted', $article, $before, ['status' => 'in_review']);

        return back()->with('status', 'مطلب برای تأیید مدیر فرستاده شد');
    }

    public function publish(Request $request, Article $article, AuditWriter $audit): RedirectResponse
    {
        abort_unless($request->user()->can('articles.publish'), 403);
        abort_unless($article->status === 'in_review' && $article->categories()->exists(), 409);
        $before = ['status' => $article->status];
        $article->update(['status' => 'published', 'published_by' => $request->user()->id, 'published_at' => now()]);
        $audit->write($request->user(), 'magazine.article.published', $article, $before, ['status' => 'published']);

        return back()->with('status', 'مطلب در مجله منتشر شد');
    }

    public function unpublish(Request $request, Article $article, AuditWriter $audit): RedirectResponse
    {
        abort_unless($request->user()->can('articles.publish'), 403);
        abort_unless($article->status === 'published', 409);
        $before = ['status' => $article->status, 'published_at' => $article->published_at];
        $article->update(['status' => 'draft', 'published_by' => null, 'published_at' => null]);
        $audit->write($request->user(), 'magazine.article.unpublished', $article, $before, ['status' => 'draft']);

        return back()->with('status', 'انتشار مطلب متوقف شد و به پیش‌نویس برگشت');
    }

    public function cover(Request $request, Article $article): StreamedResponse
    {
        abort_unless($request->user()->can('articles.edit') || $request->user()->can('articles.publish'), 403);
        abort_unless($article->cover_disk && $article->cover_path, 404);

        return Storage::disk($article->cover_disk)->response($article->cover_path, null, [
            'Content-Type' => $article->cover_mime,
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    private function save(SaveArticleRequest $request, Article $article, AuditWriter $audit): Article
    {
        $data = $request->safe()->except(['categories', 'cover']);
        $oldCover = $article->exists ? [$article->cover_disk, $article->cover_path] : [null, null];
        $newCoverPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $dimensions = getimagesize($file->getRealPath());
            $mime = $dimensions['mime'] ?? '';
            $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
            if (! isset($extensions[$mime]) || $file->getMimeType() !== $mime) {
                throw ValidationException::withMessages(['cover' => 'امضای واقعی فایل با نوع تصویر سازگار نیست']);
            }
            $newCoverPath = 'magazine/'.now()->format('Y/m').'/'.Str::ulid().'.'.$extensions[$mime];
            if (! Storage::disk('local')->put($newCoverPath, $file->getContent())) {
                throw ValidationException::withMessages(['cover' => 'تصویر ذخیره نشد؛ دوباره تلاش کنید']);
            }
            $data += ['cover_disk' => 'local', 'cover_path' => $newCoverPath, 'cover_mime' => $mime];
        }

        try {
            DB::transaction(function () use ($request, $article, $data, $audit): void {
                $before = $article->exists ? $article->only(['title', 'slug', 'status']) : null;
                if (! $article->exists) {
                    $data['author_id'] = $request->user()->id;
                    $data['status'] = 'draft';
                }
                $article->fill($data)->save();
                $article->categories()->sync($request->validated('categories'));
                $audit->write($request->user(), $before ? 'magazine.article.updated' : 'magazine.article.created', $article, $before, $article->only(['title', 'slug', 'status']));
            });
        } catch (\Throwable $exception) {
            if ($newCoverPath) {
                Storage::disk('local')->delete($newCoverPath);
            }
            throw $exception;
        }

        if ($newCoverPath && $oldCover[0] && $oldCover[1]) {
            Storage::disk($oldCover[0])->delete($oldCover[1]);
        }

        return $article->fresh();
    }

    private function categories()
    {
        return ArticleCategory::query()->where('is_active', true)->orderBy('sort_order')->orderBy('title')->get();
    }
}
