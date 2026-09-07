<?php

namespace App\Models;

use App\Enums\PlayEventType;
use App\Models\Concerns\HasPublicUlid;
use App\Models\Concerns\PreventsMutation;
use Illuminate\Database\Eloquent\Model;

class PlayEvent extends Model
{
    use HasPublicUlid, PreventsMutation;

    public $timestamps = false;

    protected $fillable = [
        'play_session_id', 'event_type', 'idempotency_key',
        'payload_json', 'occurred_at', 'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'event_type' => PlayEventType::class,
            'payload_json' => 'array',
            'occurred_at' => 'immutable_datetime',
            'recorded_at' => 'immutable_datetime',
        ];
    }
}
