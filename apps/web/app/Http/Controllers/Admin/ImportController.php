<?php

namespace App\Http\Controllers\Admin;

use App\Content\ContentImportService;
use App\Content\GameFormOptions;
use App\Content\TeelleXlsxGameReader;
use App\Http\Controllers\Controller;
use App\Models\ContentImportBatch;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportController extends Controller
{
    public function index(Request $request, GameFormOptions $formOptions): View
    {
        abort_unless($request->user()->can('content.edit'), 403);

        return view('admin.imports.index', [
            'batches' => ContentImportBatch::query()->latest()->paginate(20),
            'options' => $formOptions->all(),
        ]);
    }

    public function previewExcel(Request $request, TeelleXlsxGameReader $reader, ContentImportService $service): RedirectResponse
    {
        abort_unless($request->user()->can('content.edit'), 403);
        $data = $request->validate(['workbook' => ['required', 'file', 'max:10240']]);
        $batch = $service->previewPayload($request->user(), $reader->read($data['workbook']));

        return redirect()->route('admin.content.imports.show', $batch)->with('status', 'فایل Excel بررسی شد؛ هنوز هیچ بازی ایجاد نشده است');
    }

    public function template(Request $request): BinaryFileResponse
    {
        abort_unless($request->user()->can('content.edit'), 403);
        $path = base_path('../../docs/14-operations/templates/Teelle_New_Game_Template_v2.xlsx');
        abort_unless(is_file($path), 404);

        return response()->download($path, 'Teelle_New_Game_Template_v2.xlsx');
    }

    public function previewForm(Request $request, ContentImportService $service): RedirectResponse
    {
        abort_unless($request->user()->can('content.edit'), 403);
        $game = is_array($request->input('game')) ? $request->input('game') : [];
        $metadata = is_array($game['metadata'] ?? null) ? $game['metadata'] : [];
        $game['instructions'] = $this->parts((string) ($game['instructions_text'] ?? ''));
        $game['contraindications'] = $this->parts((string) ($game['contraindications_text'] ?? ''));
        unset($game['instructions_text'], $game['contraindications_text']);
        $metadata['required_adult'] = $request->boolean('game.metadata.required_adult');
        $metadata['materials'] = array_values(array_filter(array_map(function ($material): ?array {
            if (! is_array($material) || trim((string) ($material['slug'] ?? '')) === '') {
                return null;
            }

            return [
                'slug' => trim((string) $material['slug']),
                'requirement' => trim((string) ($material['requirement'] ?? '')),
                'quantity_note' => trim((string) ($material['quantity_note'] ?? '')) ?: null,
            ];
        }, is_array($metadata['materials'] ?? null) ? $metadata['materials'] : [])));
        foreach (['situations', 'locations', 'moods', 'tags', 'safety_flags'] as $field) {
            $metadata[$field] = array_values(array_filter(array_unique(array_map('strval', is_array($metadata[$field] ?? null) ? $metadata[$field] : []))));
        }
        $game['metadata'] = $metadata;
        $batch = $service->previewPayload($request->user(), [$game]);

        return redirect()->route('admin.content.imports.show', $batch)->with('status', 'بازی بررسی شد؛ هنوز چیزی به فهرست اضافه نشده است');
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

        return $this->attempt(fn () => $service->confirm($request->user(), $batch), back()->with('status', 'همه بازی‌های این فایل به‌صورت پیش‌نویس اضافه شدند'));
    }

    public function rollback(Request $request, ContentImportBatch $batch, ContentImportService $service): RedirectResponse
    {
        abort_unless($request->user()->can('content.edit'), 403);

        return $this->attempt(fn () => $service->rollback($request->user(), $batch), back()->with('status', 'پیش‌نویس‌های این ورود گروهی بازگردانی شدند'));
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

    /** @return array<int, string> */
    private function parts(string $value): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/[|\r\n]+/u', $value) ?: [])));
    }
}
