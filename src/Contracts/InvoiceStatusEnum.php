<?php

namespace Ako\Zarinpal\Php\Contracts;

enum InvoiceStatusEnum: string
{
    case PENDING = "PENDING";
    case IN_PROGRESS = "IN_PROGRESS";
    case PAID = "PAID";
}
