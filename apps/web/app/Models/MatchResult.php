<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchResult extends Model
{
    protected $fillable = [
        'match_session_id', 'rank', 'game_id', 'game_version_id', 'score', 'explanation_json',
    ];

    protected function casts(): array
    {
        return ['explanation_json' => 'array'];
    }
}
