<?php

namespace Ako\Zarinpal\Php\Contracts;

enum InstantPayoutMethodEnum: string
{
    case INTERNAL = "INTERNAL";
    case RTGS = "RTGS";
    case ACH = "ACH";
    case IPS = "IPS";
}
