<?php

namespace Ako\Zarinpal\Php\Contracts;

enum PayoutTypeEnum: string
{
    case FLOATING = "FLOATING";
    case FIXED = "FIXED";
    case OFFLINE = "OFFLINE";
    case TWO_STEP = "TWO_STEP";
}
