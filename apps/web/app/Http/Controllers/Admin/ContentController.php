<?php

namespace App\Http\Controllers\Admin;

use App\Content\GameContentWorkflow;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReviewContentRequest;
use App\Http\Requests\Admin\SaveGameDraftRequest;
use App\Models\AuditLog;
use App\Models\Game;
use App\Models\GameVersion;
use App\Models\MediaAsset;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->can('content.edit') || $request->user()->can('content.review'), 403);

        return view('admin.content.index', [
            'games' => Game::query()->with(['versions' => fn ($query) => $query->latest('version_no')])->latest()->paginate(25),
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

    public function edit(Request $request, GameVersion $version): View
    {
        $this->authorizeAbility($request, 'content.edit');

        return view('admin.content.form', [
            'version' => $version->load('game'), 'source' => null, 'game' => $version->game,
            'media' => MediaAsset::query()->select('media_assets.*')
                ->join('game_media', 'game_media.media_asset_id', '=', 'media_assets.id')
                ->where('game_media.game_version_id', $version->id)->get(),
            'locations' => DB::table('locations')->where('is_active', true)->orderBy('title')->get(),
            'playerRequirements' => DB::table('player_requirements')->where('is_active', true)->orderBy('title')->get(),
            'safetyRules' => DB::table('safety_rules')->where('is_active', true)->orderBy('code')->get(),
        ]);
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

    public function metadata(Request $request, GameVersion $version, GameContentWorkflow $workflow): RedirectResponse
    {
        $this->authorizeAbility($request, 'content.edit');
        $data = $request->validate([
            'minimum_age_months' => ['required', 'integer', 'min:6', 'max:155'],
            'maximum_age_months_exclusive' => ['required', 'integer', 'gt:minimum_age_months', 'max:156'],
            'location_id' => ['required', 'integer', 'exists:locations,id'],
            'player_requirement_id' => ['required', 'integer', 'exists:player_requirements,id'],
            'safety_rule_id' => ['required', 'integer', 'exists:safety_rules,id'],
        ]);

        return $this->attempt(fn () => $workflow->updateRequiredMetadata($request->user(), $version, $data), back()->with('status', 'حداقل متادیتای انتشار ذخیره شد'));
    }

    public function review(ReviewContentRequest $request, GameVersion $version, GameContentWorkflow $workflow): RedirectResponse
    {
        return $this->attempt(fn () => $workflow->review($request->user(), $version, $request->string('decision')->toString(), $request->string('notes')->toString() ?: null),
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
