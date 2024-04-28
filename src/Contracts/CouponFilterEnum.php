<?php

namespace Ako\Zarinpal\Php\Contracts;

enum CouponFilterEnum: string
{
    case ALL = "ALL";
    case ACTIVE = "ACTIVE";
    case FINISHED = "FINISHED";
    case EXPIRED = "EXPIRED";
    case DELETED = "DELETED";
    case ALL_NOT_ACTIVE = "ALL_NOT_ACTIVE";
}
