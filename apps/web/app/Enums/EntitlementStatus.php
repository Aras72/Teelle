<?php

namespace App\Enums;

enum EntitlementStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Expired = 'expired';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';
    case Revoked = 'revoked';
}
