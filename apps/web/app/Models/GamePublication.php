<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePublication extends Model
{
    protected $fillable = ['game_id', 'game_version_id', 'published_by', 'unpublished_by', 'published_at', 'unpublished_at', 'unpublish_reason'];

    protected function casts(): array
    {
        return ['published_at' => 'immutable_datetime', 'unpublished_at' => 'immutable_datetime'];
    }
}
