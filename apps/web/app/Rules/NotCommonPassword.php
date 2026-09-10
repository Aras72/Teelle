<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class NotCommonPassword implements ValidationRule
{
    private const COMMON = [
        'password', 'password1', 'password123', 'qwerty', 'qwerty123', '12345678', '123456789',
        'admin', 'admin123', 'administrator', 'letmein', 'welcome', 'welcome123', 'iloveyou',
        'abc123', 'football', 'monkey', 'dragon', 'master', 'sunshine', 'princess', 'login',
        'teelle', 'teelle123', 'رمزعبور', 'گذرواژه',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $normalized = preg_replace('/[^\pL\pN]+/u', '', mb_strtolower((string) $value)) ?? '';
        $tooRepetitive = mb_strlen($normalized) > 0 && count(array_unique(mb_str_split($normalized))) < 5;

        if (in_array($normalized, self::COMMON, true) || $tooRepetitive) {
            $fail('این رمز عبور بیش از حد رایج است');
        }
    }
}
