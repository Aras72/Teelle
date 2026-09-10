<?php

namespace App\Models;

use App\Enums\PlayState;
use App\Models\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaySession extends Model
{
    use HasPublicUlid;

    protected $fillable = [
        'match_result_id', 'user_id', 'guest_identity_id', 'state',
        'started_at', 'completed_at', 'abandoned_at',
    ];

    protected function casts(): array
    {
        return [
            'state' => PlayState::class,
            'started_at' => 'immutable_datetime',
            'completed_at' => 'immutable_datetime',
            'abandoned_at' => 'immutable_datetime',
        ];
    }

    public function result(): BelongsTo
    {
        return $this->belongsTo(MatchResult::class, 'match_result_id');
    }

    public function guestIdentity(): BelongsTo
    {
        return $this->belongsTo(GuestIdentity::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(PlayEvent::class);
    }
}
