<?php

namespace Ako\Zarinpal\Php\Contracts;

enum ReferrerInvoiceStatusEnum: string
{
    case PENDING = "PENDING";
    case PAID = "PAID";
}
