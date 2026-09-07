<?php

namespace App\Enums;

enum PlayEventType: string
{
    case Started = 'started';
    case Completed = 'completed';
    case Rated = 'rated';
}
