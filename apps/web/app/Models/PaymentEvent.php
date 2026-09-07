<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUlid;
use App\Models\Concerns\PreventsMutation;
use Illuminate\Database\Eloquent\Model;

class PaymentEvent extends Model
{
    use HasPublicUlid, PreventsMutation;

    public $timestamps = false;

    protected $fillable = [
        'purchase_id', 'provider', 'provider_event_id', 'event_type',
        'normalized_payload_json', 'payload_checksum', 'occurred_at', 'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'normalized_payload_json' => 'array',
            'occurred_at' => 'immutable_datetime',
            'recorded_at' => 'immutable_datetime',
        ];
    }
}
