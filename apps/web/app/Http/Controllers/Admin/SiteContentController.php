<?php

namespace App\Http\Controllers\Admin;

use App\Content\AuditWriter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteContentRequest;
use App\Models\SiteContentSetting;
use App\Site\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class SiteContentController extends Controller
{
    public function edit(Request $request, SiteContent $content): View
    {
        abort_unless($request->user()->can('site.manage'), 403);

        return view('admin.site-content.edit', ['content' => $content->all()]);
    }

    public function update(UpdateSiteContentRequest $request, SiteContent $content, AuditWriter $audit): RedirectResponse
    {
        $before = $content->all();
        $values = $request->validated();
        $target = DB::transaction(function () use ($request, $values): SiteContentSetting {
            $target = null;
            foreach ($values as $key => $value) {
                $target = SiteContentSetting::query()->updateOrCreate(
                    ['key' => $key], ['value' => trim((string) $value), 'updated_by' => $request->user()->id]
                );
            }

            return $target;
        });

        $content->forget();
        $audit->write($request->user(), 'site.content.updated', $target, $before, $content->all(), 'ویرایش کنترل‌شده متن و چیدمان عمومی');

        return back()->with('status', 'متن‌ها و چیدمان عمومی ذخیره شد');
    }
}
