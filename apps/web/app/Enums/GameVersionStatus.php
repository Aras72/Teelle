<?php

namespace App\Enums;

enum GameVersionStatus: string
{
    case Draft = 'draft';
    case InReview = 'in_review';
    case Approved = 'approved';
}
