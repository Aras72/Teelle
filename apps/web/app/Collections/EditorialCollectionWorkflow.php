<?php

declare(strict_types=1);

namespace App\Collections;

use App\Content\AuditWriter;
use App\Models\EditorialCollection;
use App\Models\User;
use DomainException;
use Illuminate\Support\Facades\DB;

final class EditorialCollectionWorkflow
{
    public function __construct(private readonly PublicCollectionCatalog $catalog, private readonly AuditWriter $audit) {}

    public function create(User $actor, array $data): EditorialCollection
    {
        return DB::transaction(function () use ($actor, $data): EditorialCollection {
            $collection = EditorialCollection::query()->create([
                'title' => trim($data['title']), 'slug' => $data['slug'], 'summary' => trim($data['summary']),
                'status' => 'draft', 'created_by' => $actor->id, 'updated_by' => $actor->id,
            ]);
            $this->syncGames($collection, $data['game_ids']);
            $this->audit->write($actor, 'content.collection.created', $collection, null, $this->snapshot($collection));

            return $collection;
        });
    }

    public function update(User $actor, EditorialCollection $collection, array $data): void
    {
        if ($collection->status !== 'draft') {
            throw new DomainException('فقط مجموعه پیش‌نویس قابل ویرایش است');
        }

        DB::transaction(function () use ($actor, $collection, $data): void {
            $before = $this->snapshot($collection);
            $collection->update(['title' => trim($data['title']), 'slug' => $data['slug'], 'summary' => trim($data['summary']), 'updated_by' => $actor->id]);
            $this->syncGames($collection, $data['game_ids']);
            $this->audit->write($actor, 'content.collection.updated', $collection, $before, $this->snapshot($collection));
        });
    }

    public function publish(User $actor, EditorialCollection $collection): void
    {
        if ($collection->status !== 'draft') {
            throw new DomainException('فقط مجموعه پیش‌نویس قابل انتشار است');
        }

        $ids = $collection->games()->pluck('games.id')->all();
        if ($ids === [] || count($this->catalog->eligibleIds($ids)) !== count($ids)) {
            throw new DomainException('همه بازی‌های مجموعه باید منتشر، بازبینی‌شده و دارای ایمنی و تصویر معتبر باشند');
        }

        DB::transaction(function () use ($actor, $collection): void {
            $before = $this->snapshot($collection);
            $collection->update(['status' => 'published', 'published_at' => now(), 'published_by' => $actor->id, 'updated_by' => $actor->id]);
            $this->audit->write($actor, 'content.collection.published', $collection, $before, $this->snapshot($collection));
        });
    }

    public function unpublish(User $actor, EditorialCollection $collection, string $reason): void
    {
        if ($collection->status !== 'published') {
            throw new DomainException('مجموعه در حال حاضر منتشر نیست');
        }

        DB::transaction(function () use ($actor, $collection, $reason): void {
            $before = $this->snapshot($collection);
            $collection->update(['status' => 'draft', 'published_at' => null, 'published_by' => null, 'updated_by' => $actor->id]);
            $this->audit->write($actor, 'content.collection.unpublished', $collection, $before, $this->snapshot($collection), trim($reason));
        });
    }

    private function syncGames(EditorialCollection $collection, array $ids): void
    {
        $collection->games()->sync(collect($ids)->values()->mapWithKeys(fn (int|string $id, int $position): array => [(int) $id => ['position' => $position + 1]])->all());
    }

    private function snapshot(EditorialCollection $collection): array
    {
        return ['slug' => $collection->slug, 'title' => $collection->title, 'status' => $collection->status, 'game_ids' => $collection->games()->pluck('games.id')->all()];
    }
}
