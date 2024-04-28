<?php

namespace Ako\Zarinpal\Php\Contracts;

enum InstantPayoutTypeEnum: string
{
    case INTERNAL = "INTERNAL";
    case PAYA = "PAYA";
    case SATNA = "SATNA";
}
