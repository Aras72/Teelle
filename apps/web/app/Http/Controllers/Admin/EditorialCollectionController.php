<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Collections\EditorialCollectionWorkflow;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveEditorialCollectionRequest;
use App\Models\EditorialCollection;
use App\Models\Game;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

final class EditorialCollectionController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->can('content.edit'), 403);

        return view('admin.collections.index', ['collections' => EditorialCollection::query()->withCount('games')->latest()->paginate(25)]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->can('content.edit'), 403);

        return $this->form(null);
    }

    public function store(SaveEditorialCollectionRequest $request, EditorialCollectionWorkflow $workflow): RedirectResponse
    {
        $collection = $workflow->create($request->user(), $request->validated());

        return redirect()->route('admin.content.collections.edit', $collection)->with('status', 'پیش‌نویس مجموعه ساخته شد');
    }

    public function edit(Request $request, EditorialCollection $collection): View
    {
        abort_unless($request->user()->can('content.edit'), 403);

        return $this->form($collection->load('games'));
    }

    public function update(SaveEditorialCollectionRequest $request, EditorialCollection $collection, EditorialCollectionWorkflow $workflow): RedirectResponse
    {
        return $this->attempt(fn () => $workflow->update($request->user(), $collection, $request->validated()), back()->with('status', 'مجموعه ذخیره شد'));
    }

    public function publish(Request $request, EditorialCollection $collection, EditorialCollectionWorkflow $workflow): RedirectResponse
    {
        abort_unless($request->user()->can('content.publish'), 403);

        return $this->attempt(fn () => $workflow->publish($request->user(), $collection), back()->with('status', 'مجموعه منتشر شد'));
    }

    public function unpublish(Request $request, EditorialCollection $collection, EditorialCollectionWorkflow $workflow): RedirectResponse
    {
        abort_unless($request->user()->can('content.publish'), 403);
        $data = $request->validate(['reason' => ['required', 'string', 'min:5', 'max:500']]);

        return $this->attempt(fn () => $workflow->unpublish($request->user(), $collection, $data['reason']), back()->with('status', 'انتشار مجموعه متوقف شد'));
    }

    private function form(?EditorialCollection $collection): View
    {
        return view('admin.collections.form', [
            'collection' => $collection,
            'games' => Game::query()->with('currentPublishedVersion')->orderBy('id')->get(),
            'selected' => $collection?->games->pluck('id')->all() ?? [],
        ]);
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
