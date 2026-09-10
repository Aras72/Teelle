<?php

declare(strict_types=1);

namespace App\Jigari;

use App\Enums\EntitlementStatus;
use App\Models\Entitlement;
use App\Models\User;

final class JigariAccess
{
    public function activeFor(User $user): bool
    {
        $now = now();

        return Entitlement::query()
            ->where('user_id', $user->id)
            ->where('product_code', 'jigari')
            ->where('status', EntitlementStatus::Active)
            ->whereNull('revoked_at')
            ->where('starts_at', '<=', $now)
            ->where('ends_at', '>', $now)
            ->exists();
    }
}
