<?php

namespace App\Enums;

enum GameStatus: string
{
    case Draft = 'draft';
    case InReview = 'in_review';
    case Approved = 'approved';
    case Published = 'published';
    case Unpublished = 'unpublished';
    case Archived = 'archived';
}
