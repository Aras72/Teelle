<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Model;

class ContentImportBatch extends Model
{
    use HasPublicUlid;

    protected $fillable = ['actor_user_id', 'status', 'payload_json', 'manifest_json', 'confirmed_at', 'rolled_back_at'];

    protected function casts(): array
    {
        return ['payload_json' => 'array', 'manifest_json' => 'array', 'confirmed_at' => 'immutable_datetime', 'rolled_back_at' => 'immutable_datetime'];
    }
}
