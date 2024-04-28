<?php

namespace Ako\Zarinpal\Php\Contracts;

enum FeeGroupTypeEnum: string
{
    case TERMINAL = "TERMINAL";
    case PAYOUT = "PAYOUT";
    case INSTANT_PAYOUT = "INSTANT_PAYOUT";
    case INVOICE = "INVOICE";
}
