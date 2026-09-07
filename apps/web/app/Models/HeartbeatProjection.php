<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeartbeatProjection extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['key', 'started_count', 'last_event_recorded_at'];

    protected function casts(): array
    {
        return ['last_event_recorded_at' => 'immutable_datetime'];
    }
}
