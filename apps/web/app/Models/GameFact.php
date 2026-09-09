<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameFact extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['required_adult' => 'boolean'];
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(GameVersion::class, 'game_version_id');
    }
}
