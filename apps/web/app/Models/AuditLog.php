<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUlid;
use App\Models\Concerns\PreventsMutation;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasPublicUlid, PreventsMutation;

    protected $fillable = [
        'actor_user_id', 'actor_type', 'action', 'target_type', 'target_id',
        'before_json', 'after_json', 'request_id', 'reason', 'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'before_json' => 'array',
            'after_json' => 'array',
            'occurred_at' => 'immutable_datetime',
        ];
    }
}
