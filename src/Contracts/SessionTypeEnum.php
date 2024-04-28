<?php

namespace Ako\Zarinpal\Php\Contracts;

enum SessionTypeEnum: string
{
    case ZARINGATE = "ZARINGATE";
    case ZARINAK = "ZARINAK";
    case USSDGATE = "USSDGATE";
    case ZARINLINK = "ZARINLINK";
    case PERSONALLINK = "PERSONALLINK";
    case REQUESTMONEY = "REQUESTMONEY";
    case ADDFUND = "ADDFUND";
    case POSGATE = "POSGATE";
    case BILL = "BILL";
    case TWO_STEP = "TWO_STEP";
    case DISPUTED = "DISPUTED";
    case DIRECT_DEBIT = "DIRECT_DEBIT";
    case RETURNED_INSTANT_PAYOUT = "RETURNED_INSTANT_PAYOUT";
}
