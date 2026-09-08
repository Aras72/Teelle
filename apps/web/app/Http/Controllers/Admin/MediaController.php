<?php

namespace App\Http\Controllers\Admin;

use App\Content\AuditWriter;
use App\Content\GameContentWorkflow;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UploadMediaRequest;
use App\Models\GameVersion;
use App\Models\MediaAsset;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    public function show(Request $request, MediaAsset $asset): StreamedResponse
    {
        abort_unless($request->user()->can('content.edit') || $request->user()->can('content.review'), 403);
        abort_unless(in_array($asset->mime, ['image/jpeg', 'image/png', 'image/webp'], true), 404);

        return Storage::disk($asset->disk)->response($asset->path, null, [
            'Content-Type' => $asset->mime,
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    public function store(UploadMediaRequest $request, GameVersion $version, AuditWriter $audit): RedirectResponse
    {
        if ($version->status->value !== 'draft') {
            throw ValidationException::withMessages(['image' => 'رسانه فقط به پیش‌نویس متصل می‌شود']);
        }
        $file = $request->file('image');
        $realPath = $file->getRealPath();
        $dimensions = getimagesize($realPath);
        $mime = $dimensions['mime'] ?? '';
        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (! isset($extensions[$mime]) || $file->getMimeType() !== $mime) {
            throw ValidationException::withMessages(['image' => 'امضای واقعی فایل با نوع تصویر سازگار نیست']);
        }
        $checksum = hash_file('sha256', $realPath);
        if (MediaAsset::query()->where('checksum', $checksum)->exists()) {
            throw ValidationException::withMessages(['image' => 'این فایل قبلاً بارگذاری شده است']);
        }
        $path = 'quarantine/'.now()->format('Y/m').'/'.Str::ulid().'.'.$extensions[$mime];
        if (! Storage::disk('local')->put($path, $file->getContent())) {
            throw ValidationException::withMessages(['image' => 'ذخیره امن تصویر انجام نشد؛ دوباره تلاش کنید']);
        }
        try {
            DB::transaction(function () use ($request, $version, $file, $dimensions, $mime, $checksum, $path, $audit): void {
                $asset = MediaAsset::query()->create([
                    'uploaded_by' => $request->user()->id, 'disk' => 'local', 'path' => $path,
                    'original_name' => basename($file->getClientOriginalName()), 'mime' => $mime,
                    'width' => $dimensions[0], 'height' => $dimensions[1], 'checksum' => $checksum,
                    'status' => 'quarantined', 'alt_text' => $request->string('alt_text')->toString(),
                ]);
                DB::table('game_media')->insert([
                    'game_version_id' => $version->id, 'media_asset_id' => $asset->id,
                    'role' => $request->string('role')->toString(), 'sort_order' => $request->integer('sort_order'),
                    'crop_data' => $request->string('crop_json')->toString(),
                ]);
                $audit->write($request->user(), 'content.media.quarantined', $asset, null, ['version_id' => $version->id, 'mime' => $mime]);
            });
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw $exception;
        }

        return back()->with('status', 'تصویر در قرنطینه ثبت شد و باید مستقل بازبینی شود');
    }

    public function review(Request $request, MediaAsset $asset, GameContentWorkflow $workflow): RedirectResponse
    {
        abort_unless($request->user()->can('content.review'), 403);
        try {
            $workflow->reviewMedia($request->user(), $asset);
        } catch (DomainException $exception) {
            throw ValidationException::withMessages(['media' => $exception->getMessage()]);
        }

        return back()->with('status', 'رسانه تأیید شد');
    }
}
