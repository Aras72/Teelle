<?php

namespace App\Models;

use App\Models\Concerns\PreventsMutation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentReview extends Model
{
    use PreventsMutation;

    protected $fillable = ['game_version_id', 'reviewer_id', 'decision', 'scope_hash', 'scope_decisions', 'notes', 'invalidation_reason', 'reviewed_at'];

    protected function casts(): array
    {
        return ['scope_decisions' => 'array', 'reviewed_at' => 'immutable_datetime'];
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(GameVersion::class, 'game_version_id');
    }
}
