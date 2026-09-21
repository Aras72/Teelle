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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $versioned = 'Teelle_New_Game_Template_v3.xlsx';
        $path = base_path('../../docs/14-operations/templates/'.$versioned);
        abort_unless(is_file($path), 404);

        return response()->download($path, $versioned);
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
            $metadata[$field] = $this->uniqueList($metadata[$field] ?? null);
        }
        // DEC-060: چک‌باکس‌های «گزینه‌های دیگر» فرم به‌صورت جدا (game[metadata_extra]) ارسال می‌شوند؛
        // مقدار اصلی همان منوی کشویی می‌ماند و بقیه انتخاب‌ها به‌عنوان جانبی ذخیره می‌شوند.
        $extras = is_array($game['metadata_extra'] ?? null) ? $game['metadata_extra'] : [];
        foreach (['space_required', 'noise_level', 'mess_level', 'interaction_type', 'caregiver_involvement',
            'setup_complexity', 'player_requirement', 'child_energy', 'caregiver_energy'] as $field) {
            $alternatives = $this->uniqueList($extras[$field] ?? null);
            $primary = trim((string) ($metadata[$field] ?? ''));
            $alternatives = array_values(array_diff($alternatives, [$primary]));
            if ($alternatives !== []) {
                $metadata['alternatives'][$field] = $alternatives;
            }
        }
        $supervisionExtras = array_values(array_diff($this->uniqueList($extras['supervision_level'] ?? null), [trim((string) ($game['supervision_level'] ?? ''))]));
        if ($supervisionExtras !== []) {
            $metadata['alternatives']['supervision_level'] = $supervisionExtras;
        }
        $metadata['alternatives'] = array_filter(array_map(function ($extra): array {
            return array_values(array_filter(array_unique(array_map('strval', is_array($extra) ? $extra : []))));
        }, is_array($metadata['alternatives'] ?? null) ? $metadata['alternatives'] : []));
        $metadata['content_priority'] = in_array((string) ($metadata['content_priority'] ?? 'normal'), ['high', 'normal', 'low'], true)
            ? (string) ($metadata['content_priority'] ?? 'normal') : 'normal';
        $metadata['priority_reason'] = trim((string) ($metadata['priority_reason'] ?? '')) ?: null;
        $game['metadata'] = $metadata;
        $batch = $service->previewPayload($request->user(), [$game]);
        $this->attachQuarantinedImage($request, $batch);

        return redirect()->route('admin.content.imports.show', $batch)->with('status', 'بازی بررسی شد؛ هنوز چیزی به فهرست اضافه نشده است');
    }

    /**
     * تصویر اختیاری فرم افزودن بازی در قرنطینه ذخیره و به بسته ایمپورت وصل می‌شود؛
     * پس از تأیید بسته، به پیش‌نویس ساخته‌شده منتقل می‌شود و مسیر بازبینی مستقل رسانه حفظ است.
     */
    private function attachQuarantinedImage(Request $request, ContentImportBatch $batch): void
    {
        if (! $request->hasFile('game.image')) {
            return;
        }
        $file = $request->file('game.image');
        $realPath = (string) $file->getRealPath();
        $dimensions = getimagesize($realPath);
        $mime = $dimensions['mime'] ?? '';
        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (! isset($extensions[$mime]) || $file->getMimeType() !== $mime) {
            throw ValidationException::withMessages(['game.image' => 'امضای واقعی فایل با نوع تصویر سازگار نیست']);
        }
        $checksum = hash_file('sha256', $realPath);
        if (DB::table('media_assets')->where('checksum', $checksum)->exists()) {
            throw ValidationException::withMessages(['game.image' => 'این فایل قبلاً بارگذاری شده است']);
        }
        $path = 'quarantine/'.now()->format('Y/m').'/'.Str::ulid().'.'.$extensions[$mime];
        if (! Storage::disk('local')->put($path, $file->getContent())) {
            throw ValidationException::withMessages(['game.image' => 'ذخیره امن تصویر انجام نشد؛ دوباره تلاش کنید']);
        }
        try {
            $assetId = DB::table('media_assets')->insertGetId([
                'public_id' => (string) Str::ulid(), 'uploaded_by' => $request->user()->id, 'disk' => 'local', 'path' => $path,
                'original_name' => basename($file->getClientOriginalName()), 'mime' => $mime,
                'width' => $dimensions[0], 'height' => $dimensions[1], 'checksum' => $checksum,
                'status' => 'quarantined', 'alt_text' => trim((string) $request->input('game.image_alt_text')) ?: null,
                'created_at' => now(), 'updated_at' => now(),
            ]);
            $batch->forceFill(['media_asset_id' => $assetId])->save();
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw $exception;
        }
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

    /**
     * ورودی چندگانه فرم/اکسل را به فهرست یکتا و تمیز تبدیل می‌کند؛
     * وقتی منبع مقدار واحدی است (مثل منوی کشویی) خروجی تک‌عضوی است.
     *
     * @return array<int, string>
     */
    private function uniqueList(mixed $value): array
    {
        if (is_string($value)) {
            $value = explode(',', $value);
        }
        if (! is_array($value)) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map('trim', array_map('strval', $value)))));
    }
}
