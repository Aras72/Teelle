<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EditorialCollection extends Model
{
    use HasPublicUlid;

    protected $fillable = ['slug', 'title', 'summary', 'status', 'created_by', 'updated_by', 'published_by', 'published_at'];

    protected function casts(): array
    {
        return ['published_at' => 'immutable_datetime'];
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'editorial_collection_game')->withPivot('position')->orderByPivot('position');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
