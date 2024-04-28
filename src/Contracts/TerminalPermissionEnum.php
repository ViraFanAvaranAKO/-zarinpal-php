<?php

namespace Ako\Zarinpal\Php\Contracts;

enum TerminalPermissionEnum: string
{
    case SESSION = "SESSION";
    case SESSION_EDIT = "SESSION_EDIT";
    case PRODUCT = "PRODUCT";
    case PRODUCT_EDIT = "PRODUCT_EDIT";
    case RECONCILE = "RECONCILE";
    case RECONCILE_EDIT = "RECONCILE_EDIT";
    case TERMINAL_EDIT = "TERMINAL_EDIT";
    case PAYOUT = "PAYOUT";
    case WAGE_PAYOUT = "WAGE_PAYOUT";
    case PAYOUT_EDIT = "PAYOUT_EDIT";
    case COUPON = "COUPON";
    case COUPON_EDIT = "COUPON_EDIT";
    case INVOICE = "INVOICE";
    case INVOICE_EDIT = "INVOICE_EDIT";
    case INSTANT_PAYOUT = "INSTANT_PAYOUT";
    case INSTANT_PAYOUT_EDIT = "INSTANT_PAYOUT_EDIT";
    case REFUND = "REFUND";
    case REFUND_EDIT = "REFUND_EDIT";
    case INVESTMENT = "INVESTMENT";
    case INVESTMENT_EDIT = "INVESTMENT_EDIT";
}
