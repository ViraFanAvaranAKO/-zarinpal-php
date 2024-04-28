<?php

namespace Ako\Zarinpal\Php\Contracts;

enum SessionTryTypeEnum: string
{
    case ZARINGATE = "ZARINGATE";
    case ZARINAK = "ZARINAK";
    case USSDGATE = "USSDGATE";
    case ZARINLINK = "ZARINLINK";
    case PERSONALLINK = "PERSONALLINK";
    case REQUESTMONEY = "REQUESTMONEY";
    case ADDFUND = "ADDFUND";
}
