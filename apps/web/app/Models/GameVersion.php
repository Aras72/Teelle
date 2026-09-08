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
        'game_id', 'created_by', 'submitted_by', 'version_no', 'status', 'title', 'summary', 'instructions',
        'safety_copy', 'contraindications', 'supervision_level', 'content_hash',
        'review_quality', 'approved_at', 'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => GameVersionStatus::class,
            'instructions' => 'array',
            'contraindications' => 'array',
            'approved_at' => 'immutable_datetime',
            'submitted_at' => 'immutable_datetime',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted(): void
    {
        static::updating(function (self $version): void {
            if ($version->getOriginal('status') === GameVersionStatus::Approved->value) {
                throw new \LogicException('Approved game versions are immutable.');
            }
        });
    }
}
