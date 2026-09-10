<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Household extends Model
{
    use HasPublicUlid;

    protected $fillable = ['owner_user_id', 'title'];

    public function childProfiles(): HasMany
    {
        return $this->hasMany(ChildProfile::class);
    }
}
