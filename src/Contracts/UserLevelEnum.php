<?php

namespace Ako\Zarinpal\Php\Contracts;

enum UserLevelEnum: string
{
    case GOLD = "GOLD";
    case SILVER = "SILVER";
    case BASIC = "BASIC";
    case BLUE = "BLUE";
    case NEW = "NEW";
    case ADMIN = "ADMIN";
}
