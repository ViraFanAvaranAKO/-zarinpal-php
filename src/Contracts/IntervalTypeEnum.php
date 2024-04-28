<?php

namespace Ako\Zarinpal\Php\Contracts;

enum IntervalTypeEnum: string
{
    case DAILY = "DAILY";
    case WEEKLY = "WEEKLY";
    case MONTHLY = "MONTHLY";
}
