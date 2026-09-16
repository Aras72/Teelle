<?php

namespace App\Http\Controllers\Admin;

use App\Content\AuditWriter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveArticleCategoryRequest;
use App\Models\ArticleCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
}
