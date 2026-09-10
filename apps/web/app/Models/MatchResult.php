<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchResult extends Model
{
    protected $fillable = [
        'match_session_id', 'rank', 'game_id', 'game_version_id', 'score', 'explanation_json',
    ];

    protected function casts(): array
    {
        return ['explanation_json' => 'array'];
    }

    public function matchSession(): BelongsTo
    {
        return $this->belongsTo(MatchSession::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function gameVersion(): BelongsTo
    {
        return $this->belongsTo(GameVersion::class);
    }
}
