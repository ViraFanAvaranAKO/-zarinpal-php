<?php

namespace Ako\Zarinpal\Php\Contracts;

enum AccessTokenEnum: string
{
    case ZARINPAL = "ZARINPAL";
    case THIRDPARTY = "THIRDPARTY";
    case PERSONAL = "PERSONAL";
}
