<?php

namespace Ako\Zarinpal\Php\Contracts;

enum ReconciliationWageTypeEnum: string
{
    case INSTANT_PAYOUT = "INSTANT_PAYOUT";
    case PAYOUT = "PAYOUT";
    case REFUND = "REFUND";
    case TWO_STEP = "TWO_STEP";
}
