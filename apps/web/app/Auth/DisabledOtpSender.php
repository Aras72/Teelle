<?php

declare(strict_types=1);

namespace App\Auth;

use DomainException;

final class DisabledOtpSender implements OtpSender
{
    public function send(string $phoneE164, string $code): void
    {
        throw new DomainException('ورود با کد یک‌بارمصرف هنوز فعال نیست');
    }
}
