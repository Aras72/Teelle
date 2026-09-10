<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedGame extends Model
{
    protected $fillable = ['user_id', 'game_id'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
