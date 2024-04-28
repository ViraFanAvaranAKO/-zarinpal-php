<?php

namespace Ako\Zarinpal\Php\Contracts;

enum FilterEnum: string
{
    case ALL = "ALL";
    case ACTIVE = "ACTIVE";
    case TRASH = "TRASH";
    case REACHED_AMOUNT = "REACHED_AMOUNT";
    case PAID = "PAID";
    case VERIFIED = "VERIFIED";
    case REFUNDED = "REFUNDED";
    case REJECTED = "REJECTED";
    case REJECT_REVERSED = "REJECT_REVERSED";
}
