<?php

namespace Ako\Zarinpal\Php\Contracts;

enum SessionFeeTypeEnum: string
{
    case MERCHANT = "MERCHANT";
    case PAYER = "PAYER";
}
