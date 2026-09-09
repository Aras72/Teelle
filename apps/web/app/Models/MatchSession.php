<?php

namespace App\Models;

use App\Enums\MatchOutcome;
use App\Models\Concerns\HasPublicUlid;
use Database\Factories\MatchSessionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatchSession extends Model
{
    /** @use HasFactory<MatchSessionFactory> */
    use HasFactory, HasPublicUlid;

    protected $fillable = [
        'submission_key', 'user_id', 'guest_identity_id', 'age_months', 'context_json',
        'ruleset_version', 'outcome', 'evaluated_at', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'context_json' => 'array',
            'outcome' => MatchOutcome::class,
            'evaluated_at' => 'immutable_datetime',
            'expires_at' => 'immutable_datetime',
        ];
    }

    public function results(): HasMany
    {
        return $this->hasMany(MatchResult::class);
    }

    public function guestIdentity(): BelongsTo
    {
        return $this->belongsTo(GuestIdentity::class);
    }
}
