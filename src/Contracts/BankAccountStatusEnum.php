<?php

namespace Ako\Zarinpal\Php\Contracts;

enum BankAccountStatusEnum: string
{
    case REJECTED = "REJECTED";
    case INACTIVE = "INACTIVE";
    case ACTIVE = "ACTIVE";
    case PENDING = "PENDING";
    case ZARIN_CARD_PENDING = "ZARIN_CARD_PENDING";
    case PENDING_SHAPARAK = "PENDING_SHAPARAK";
    case REJECTED_SHAPARAK = "REJECTED_SHAPARAK";
}
