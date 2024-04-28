<?php

namespace Ako\Zarinpal\Php\Contracts;

enum SessionTryStatusEnum: string
{
    case FAILED = "FAILED";
    case INBANK = "INBANK";
    case PAID = "PAID";
    case VERIFIED = "VERIFIED";
}
