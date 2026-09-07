<?php

namespace App\Models\Concerns;

use LogicException;

trait PreventsMutation
{
    public static function bootPreventsMutation(): void
    {
        static::updating(fn () => throw new LogicException(class_basename(static::class).' records are append-only.'));
        static::deleting(fn () => throw new LogicException(class_basename(static::class).' records are append-only.'));
    }
}
