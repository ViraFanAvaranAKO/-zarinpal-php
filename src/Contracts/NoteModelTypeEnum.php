<?php

namespace Ako\Zarinpal\Php\Contracts;

enum NoteModelTypeEnum: string
{
    case TERMINAL = "TERMINAL";
    case COUPON = "COUPON";
    case INVOICE = "INVOICE";
    case PAYOUT = "PAYOUT";
    case PRODUCT = "PRODUCT";
    case RECONCILE = "RECONCILE";
    case SESSION = "SESSION";
    case USER = "USER";
    case TERMINAL_CUSTOMER = "TERMINAL_CUSTOMER";
}
