<?php

declare(strict_types=1);

namespace App\Auth;

interface OtpSender
{
    public function send(string $phoneE164, string $code): void;
}
