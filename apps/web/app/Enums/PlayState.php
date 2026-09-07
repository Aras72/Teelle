<?php

namespace App\Enums;

enum PlayState: string
{
    case Matched = 'matched';
    case Started = 'started';
    case Completed = 'completed';
    case Abandoned = 'abandoned';
}
