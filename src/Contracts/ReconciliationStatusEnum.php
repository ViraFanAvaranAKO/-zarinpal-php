<?php

namespace Ako\Zarinpal\Php\Contracts;

enum ReconciliationStatusEnum: string
{
    case ALL = "ALL";
    case REJECTED = "REJECTED";
    case PAID = "PAID";
    case PENDING = "PENDING";
    case IN_PROGRESS = "IN_PROGRESS";
    case REVERSED = "REVERSED";
    case CONFLICTED_REVERSED = "CONFLICTED_REVERSED";
    case BANK_REJECTED = "BANK_REJECTED";
}
