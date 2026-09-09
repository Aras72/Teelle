<?php

namespace App\Http\Controllers\Admin;

use App\Content\ContentImportService;
use App\Http\Controllers\Controller;
use App\Models\ContentImportBatch;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ImportController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->can('content.edit'), 403);

        return view('admin.imports.index', ['batches' => ContentImportBatch::query()->latest()->paginate(20)]);
    }

    public function preview(Request $request, ContentImportService $service): RedirectResponse
    {
        abort_unless($request->user()->can('content.edit'), 403);
        $data = $request->validate(['payload' => ['required', 'string', 'max:1000000']]);
        $batch = $service->preview($request->user(), $data['payload']);

        return redirect()->route('admin.content.imports.show', $batch)->with('status', 'پیش‌نمایش ساخته شد؛ هنوز هیچ بازی ایجاد نشده است');
    }

    public function previewPilot(Request $request, ContentImportService $service): RedirectResponse
    {
        abort_unless($request->user()->can('content.edit'), 403);
        $path = resource_path('content/pilot-games-v1.php');
        abort_unless(is_file($path), 404);
        $batch = $service->preview($request->user(), json_encode(require $path, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return redirect()->route('admin.content.imports.show', $batch)
            ->with('status', 'Pilot پیشنهادی فقط برای بازبینی آماده شد؛ هنوز هیچ بازی ایجاد نشده است');
    }

    public function show(Request $request, ContentImportBatch $batch): View
    {
        abort_unless($request->user()->can('content.edit'), 403);

        return view('admin.imports.show', ['batch' => $batch]);
    }

    public function confirm(Request $request, ContentImportBatch $batch, ContentImportService $service): RedirectResponse
    {
        abort_unless($request->user()->can('content.edit'), 403);

        return $this->attempt(fn () => $service->confirm($request->user(), $batch), back()->with('status', 'Import به‌صورت اتمیک تأیید شد'));
    }

    public function rollback(Request $request, ContentImportBatch $batch, ContentImportService $service): RedirectResponse
    {
        abort_unless($request->user()->can('content.edit'), 403);

        return $this->attempt(fn () => $service->rollback($request->user(), $batch), back()->with('status', 'پیش‌نویس‌های این Batch بازگردانی شدند'));
    }

    private function attempt(callable $operation, RedirectResponse $success): RedirectResponse
    {
        try {
            $operation();

            return $success;
        } catch (DomainException $exception) {
            throw ValidationException::withMessages(['import' => $exception->getMessage()]);
        }
    }
}
