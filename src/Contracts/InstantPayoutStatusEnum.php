<?php

namespace Ako\Zarinpal\Php\Contracts;

enum InstantPayoutStatusEnum: string
{
    case REVERSED = "REVERSED";
    case REJECTED = "REJECTED";
    case BANK_REJECTED = "BANK_REJECTED";
    case PAID = "PAID";
    case PENDING = "PENDING";
    case IN_PROGRESS = "IN_PROGRESS";
}
