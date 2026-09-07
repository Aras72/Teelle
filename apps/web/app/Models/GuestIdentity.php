<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUlid;
use Database\Factories\GuestIdentityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestIdentity extends Model
{
    /** @use HasFactory<GuestIdentityFactory> */
    use HasFactory, HasPublicUlid;

    protected $fillable = ['token_hash', 'expires_at', 'last_seen_at'];

    protected function casts(): array
    {
        return [
            'expires_at' => 'immutable_datetime',
            'last_seen_at' => 'immutable_datetime',
        ];
    }
}
