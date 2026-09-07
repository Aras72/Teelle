<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasPublicUlid;

    protected $fillable = [
        'user_id', 'plan_id', 'status', 'amount_minor', 'currency',
        'provider', 'provider_reference', 'paid_at',
    ];

    protected function casts(): array
    {
        return ['paid_at' => 'immutable_datetime'];
    }
}
