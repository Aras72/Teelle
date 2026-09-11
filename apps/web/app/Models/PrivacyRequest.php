<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrivacyRequest extends Model
{
    use HasPublicUlid;

    protected $fillable = [
        'user_id', 'request_type', 'status', 'active_key', 'requested_at',
        'scheduled_for', 'completed_at', 'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'requested_at' => 'immutable_datetime',
            'scheduled_for' => 'immutable_datetime',
            'completed_at' => 'immutable_datetime',
            'cancelled_at' => 'immutable_datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
