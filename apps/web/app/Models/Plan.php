<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['code', 'title', 'duration_months', 'price_minor', 'currency', 'is_active'];

    protected function casts(): array
    {
        return [
            'duration_months' => 'integer',
            'price_minor' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
