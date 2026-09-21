<?php

namespace App\Http\Controllers\Admin;

use App\Content\AuditWriter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveArticleCategoryRequest;
use App\Models\ArticleCategory;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ArticleCategoryController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->can('articles.edit'), 403);

        return view('admin.article-categories.index', [
            'categories' => ArticleCategory::query()->with('parent')->orderBy('sort_order')->orderBy('title')->get(),
        ]);
    }

    public function store(SaveArticleCategoryRequest $request, AuditWriter $audit): RedirectResponse
    {
        $category = ArticleCategory::query()->create($request->validated());
        $audit->write($request->user(), 'magazine.category.created', $category, null, $category->only(['title', 'slug', 'is_active']));

        return back()->with('status', 'دسته‌بندی ساخته شد');
    }

    public function update(SaveArticleCategoryRequest $request, ArticleCategory $category, AuditWriter $audit): RedirectResponse
    {
        $before = $category->only(['title', 'slug', 'description', 'parent_id', 'sort_order', 'is_active']);
        $category->update($request->validated());
        $audit->write($request->user(), 'magazine.category.updated', $category, $before, $category->fresh()->only(array_keys($before)));

        return back()->with('status', 'دسته‌بندی ذخیره شد');
    }

    public function destroy(Request $request, ArticleCategory $category, AuditWriter $audit): RedirectResponse
    {
        abort_unless($request->user()->can('articles.edit'), 403);

        $childCount = ArticleCategory::query()->where('parent_id', $category->id)->count();
        if ($childCount > 0 || $category->articles()->exists()) {
            throw ValidationException::withMessages([
                'category' => $childCount > 0
                    ? 'این دسته زیرمجموعه دارد؛ اول زیرمجموعه را جابه‌جا یا حذف کنید'
                    : 'این دسته به مطلب وصل است؛ اول مطلب را ویرایش کنید',
            ]);
        }

        try {
            DB::transaction(function () use ($request, $category, $audit): void {
                $before = $category->only(['title', 'slug', 'is_active']);
                $category->delete();
                $audit->write($request->user(), 'magazine.category.deleted', $category, $before, null);
            });
        } catch (DomainException) {
            throw ValidationException::withMessages(['category' => 'حذف دسته‌بندی ثبت نشد؛ دوباره تلاش کنید']);
        }

        return redirect()->route('admin.content.article-categories.index')->with('status', 'دسته‌بندی حذف شد');
    }
}
