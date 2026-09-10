<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChildProfile extends Model
{
    use HasPublicUlid, SoftDeletes;

    protected $fillable = ['household_id', 'nickname', 'birth_month', 'status'];

    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }
}
