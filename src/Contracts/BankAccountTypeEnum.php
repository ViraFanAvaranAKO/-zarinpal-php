<?php

namespace Ako\Zarinpal\Php\Contracts;

enum BankAccountTypeEnum: string
{
    case PERSONAL = "PERSONAL";
    case SHARE = "SHARE";
    case ZARIN_CARD = "ZARIN_CARD";
    case NEO_ZARIN = "NEO_ZARIN";
    case REFUND = "REFUND";
    case ZARIN_DAX = "ZARIN_DAX";
}
