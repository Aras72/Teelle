<?php

namespace App\Models;

use App\Enums\EntitlementStatus;
use App\Models\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Model;

class Entitlement extends Model
{
    use HasPublicUlid;

    protected $fillable = [
        'user_id', 'purchase_id', 'product_code', 'status', 'starts_at',
        'ends_at', 'revoked_at', 'revocation_reason',
    ];

    protected function casts(): array
    {
        return [
            'status' => EntitlementStatus::class,
            'starts_at' => 'immutable_datetime',
            'ends_at' => 'immutable_datetime',
            'revoked_at' => 'immutable_datetime',
        ];
    }
}
