<?php

namespace App\Models;

use App\Enums\GameStatus;
use App\Models\Concerns\HasPublicUlid;
use Database\Factories\GameFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    /** @use HasFactory<GameFactory> */
    use HasFactory, HasPublicUlid;

    protected $fillable = ['slug', 'status', 'current_published_version_id'];

    protected function casts(): array
    {
        return ['status' => GameStatus::class];
    }

    public function versions(): HasMany
    {
        return $this->hasMany(GameVersion::class);
    }

    public function currentPublishedVersion(): BelongsTo
    {
        return $this->belongsTo(GameVersion::class, 'current_published_version_id');
    }
}
