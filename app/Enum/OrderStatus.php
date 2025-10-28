<?php

namespace App\Enum;

enum OrderStatus: string
{
    case WAITING = 'WAITING';
    case IN_PROGRESS = 'IN_PROGRESS';
    case COMPLETED = 'COMPLETED';
    case CANCELED = 'CANCELED';
}
