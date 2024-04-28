<?php

namespace Ako\Zarinpal\Php\Contracts;

enum CouponStatusEnum: string
{
    case ACTIVE = "ACTIVE";
    case FINISHED = "FINISHED";
    case EXPIRED = "EXPIRED";
    case DELETED = "DELETED";
}
