<?php

namespace App\Models;

use App\Enums\GameVersionStatus;
use Database\Factories\GameVersionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameVersion extends Model
{
    /** @use HasFactory<GameVersionFactory> */
    use HasFactory;

    protected $fillable = [
        'game_id', 'version_no', 'status', 'title', 'summary', 'instructions',
        'safety_copy', 'contraindications', 'supervision_level', 'content_hash',
        'review_quality', 'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => GameVersionStatus::class,
            'instructions' => 'array',
            'contraindications' => 'array',
            'approved_at' => 'immutable_datetime',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
