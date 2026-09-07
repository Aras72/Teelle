<?php

namespace App\Enums;

enum MatchOutcome: string
{
    case Collecting = 'collecting';
    case Evaluated = 'evaluated';
    case Matched = 'matched';
    case NoResult = 'no_result';
    case Expired = 'expired';
}
