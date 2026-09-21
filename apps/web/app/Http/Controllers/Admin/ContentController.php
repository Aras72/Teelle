<?php

namespace App\Http\Controllers\Admin;

use App\Content\GameContentWorkflow;
use App\Content\GameFormOptions;
use App\Content\GameMetadataPayload;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReviewContentRequest;
use App\Http\Requests\Admin\SaveGameDraftRequest;
use App\Http\Requests\Admin\StructuredMetadataRequest;
use App\Models\AuditLog;
use App\Models\Game;
use App\Models\GameVersion;
use App\Models\MediaAsset;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(Request $request): View
    {
        $canManageGames = $request->user()->can('content.edit') || $request->user()->can('content.review') || $request->user()->can('content.publish');
        abort_unless($canManageGames || $request->user()->can('articles.edit') || $request->user()->can('articles.publish'), 403);

        return view('admin.content.index', [
            'canManageGames' => $canManageGames,
            'games' => $canManageGames
                ? Game::query()->with(['versions' => fn ($query) => $query->latest('version_no')])->latest()->paginate(25)
                : null,
            'audits' => AuditLog::query()->latest('occurred_at')->limit(15)->get(),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorizeAbility($request, 'content.edit');

        return view('admin.content.form', ['version' => null, 'source' => null, 'game' => null]);
    }

    public function store(SaveGameDraftRequest $request, GameContentWorkflow $workflow): RedirectResponse
    {
        $game = $workflow->createDraft($request->user(), $request->validated());

        return redirect()->route('admin.content.edit', $game->versions()->first())->with('status', 'پیش‌نویس ساخته شد');
    }

    public function edit(Request $request, GameVersion $version, GameMetadataPayload $metadata, GameFormOptions $formOptions): View
    {
        $this->authorizeAbility($request, 'content.edit');

        return view('admin.content.form', [
            'version' => $version->load('game'), 'source' => null, 'game' => $version->game,
            'media' => MediaAsset::query()->select('media_assets.*')
                ->join('game_media', 'game_media.media_asset_id', '=', 'media_assets.id')
                ->where('game_media.game_version_id', $version->id)->get(),
            'metadata' => $metadata->forVersion($version),
            'options' => $formOptions->all(includeInactive: true),
        ]);
    }

    public function showReview(Request $request, GameVersion $version, GameMetadataPayload $metadata): View
    {
        abort_unless($request->user()->can('content.edit') || $request->user()->can('content.review'), 403);

        $version->load(['game', 'creator', 'facts']);
        $media = MediaAsset::query()->select('media_assets.*')
            ->join('game_media', 'game_media.media_asset_id', '=', 'media_assets.id')
            ->where('game_media.game_version_id', $version->id)
            ->orderBy('game_media.sort_order')
            ->get();

        return view('admin.content.review', [
            'version' => $version,
            'metadata' => $metadata->forVersion($version),
            'media' => $media,
            'isOwnVersion' => (int) $version->created_by === (int) $request->user()->id,
        ]);
    }

    public function structuredMetadata(StructuredMetadataRequest $request, GameVersion $version, GameContentWorkflow $workflow): RedirectResponse
    {
        // DEC-060: چک‌باکس‌های «گزینه‌های دیگر» (از جمله سطح نظارت) به گزینه‌های جانبی همان فیلد وصل می‌شوند؛
        // مقدار اصلی هر فیلد همچنان در ستون خودش ذخیره می‌شود و موتور تطبیق تغییری نمی‌کند.
        $metadata = $request->validated('metadata');
        $extras = is_array($request->validated('metadata_extra')) ? $request->validated('metadata_extra') : [];
        foreach ($extras as $field => $options) {
            // در صفحه ویرایش، سطح نظارت در فرم جداگانه بالا ذخیره می‌شود؛ مقدار اصلی همین نسخه است.
            $primary = $field === 'supervision_level'
                ? (string) $version->supervision_level
                : ($metadata[$field] ?? null);
            if (is_array($primary)) {
                $primary = $primary[0] ?? null;
            }
            $metadata['alternatives'][$field] = array_values(array_diff(array_filter(array_map('strval', (array) $options)), [(string) $primary]));
            if ($metadata['alternatives'][$field] === []) {
                unset($metadata['alternatives'][$field]);
            }
        }

        return $this->attempt(fn () => $workflow->updateStructuredMetadata($request->user(), $version, $metadata),
            back()->with('status', 'Metadata کامل بازی ذخیره شد'));
    }

    public function update(SaveGameDraftRequest $request, GameVersion $version, GameContentWorkflow $workflow): RedirectResponse
    {
        return $this->attempt(fn () => $workflow->updateDraft($request->user(), $version, $request->validated()),
            redirect()->route('admin.content.edit', $version)->with('status', 'پیش‌نویس ذخیره شد'));
    }

    public function revision(Request $request, Game $game): View
    {
        $this->authorizeAbility($request, 'content.edit');

        return view('admin.content.form', ['version' => null, 'source' => $game->versions()->latest('version_no')->firstOrFail(), 'game' => $game]);
    }

    public function storeRevision(SaveGameDraftRequest $request, Game $game, GameContentWorkflow $workflow): RedirectResponse
    {
        try {
            $version = $workflow->createRevision($request->user(), $game, $request->validated());
        } catch (DomainException $exception) {
            throw ValidationException::withMessages(['workflow' => $exception->getMessage()]);
        }

        return redirect()->route('admin.content.edit', $version)->with('status', 'نسخه پیش‌نویس تازه ساخته شد');
    }

    public function submit(Request $request, GameVersion $version, GameContentWorkflow $workflow): RedirectResponse
    {
        $this->authorizeAbility($request, 'content.edit');

        return $this->attempt(fn () => $workflow->submit($request->user(), $version), back()->with('status', 'برای بازبینی ارسال شد'));
    }

    public function review(ReviewContentRequest $request, GameVersion $version, GameContentWorkflow $workflow): RedirectResponse
    {
        return $this->attempt(fn () => $workflow->review(
            $request->user(),
            $version,
            $request->string('decision')->toString(),
            $request->string('notes')->toString() ?: null,
            $request->validated('checks'),
        ),
            back()->with('status', 'نتیجه بازبینی ثبت شد'));
    }

    public function publish(Request $request, GameVersion $version, GameContentWorkflow $workflow): RedirectResponse
    {
        $this->authorizeAbility($request, 'content.publish');

        return $this->attempt(fn () => $workflow->publish($request->user(), $version), back()->with('status', 'نسخه منتشر شد'));
    }

    public function unpublish(Request $request, Game $game, GameContentWorkflow $workflow): RedirectResponse
    {
        $this->authorizeAbility($request, 'content.publish');
        $data = $request->validate(['reason' => ['required', 'string', 'min:5', 'max:500']]);

        return $this->attempt(fn () => $workflow->unpublish($request->user(), $game, $data['reason']), back()->with('status', 'انتشار فوراً متوقف شد'));
    }

    private function authorizeAbility(Request $request, string $ability): void
    {
        abort_unless($request->user()->can($ability), 403);
    }

    private function attempt(callable $operation, RedirectResponse $success): RedirectResponse
    {
        try {
            $operation();

            return $success;
        } catch (DomainException $exception) {
            throw ValidationException::withMessages(['workflow' => $exception->getMessage()]);
        }
    }
}
