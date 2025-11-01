<?php

namespace App\Enum;

enum PlanStatus: string
{
    case CREATED = 'CREATED';
    case NEEDS_APPROVAL = 'NEEDS_APPROVAL';
    case APPROVED = 'APPROVED';
    case DECLINED = 'DECLINED';
    case PROCESSED = 'PROCESSED';
}
