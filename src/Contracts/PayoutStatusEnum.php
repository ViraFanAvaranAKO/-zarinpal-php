<?php

namespace Ako\Zarinpal\Php\Contracts;

enum PayoutStatusEnum: string
{
    case ACTIVE = "ACTIVE";
    case DE_ACTIVE = "DE_ACTIVE";
    case PENDING_SHAPARAK = "PENDING_SHAPARAK";
    case REACHED_AMOUNT = "REACHED_AMOUNT";
    case PAID = "PAID";
    case REJECT = "REJECT";
    case REJECT_REVERSED = "REJECT_REVERSED";
}
