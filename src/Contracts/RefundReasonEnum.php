<?php

namespace Ako\Zarinpal\Php\Contracts;

enum RefundReasonEnum: string
{
    case DUPLICATE_TRANSACTION = "DUPLICATE_TRANSACTION";
    case SUSPICIOUS_TRANSACTION = "SUSPICIOUS_TRANSACTION";
    case CUSTOMER_REQUEST = "CUSTOMER_REQUEST";
    case OTHER = "OTHER";
}
