<?php

namespace Ako\Zarinpal\Php\Contracts;

enum SessionStatusEnum: string
{
    case FAILED = "FAILED";
    case INBANK = "INBANK";
    case PAID = "PAID";
    case VERIFIED = "VERIFIED";
    case CONFLICTED = "CONFLICTED";
}
