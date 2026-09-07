<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasPublicUlid
{
    public static function bootHasPublicUlid(): void
    {
        static::creating(function (object $model): void {
            if (blank($model->public_id)) {
                $model->public_id = (string) Str::ulid();
            }
        });
    }
}
