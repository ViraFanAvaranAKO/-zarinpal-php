<?php

namespace Ako\Zarinpal\Php\Contracts;

enum NotifyTypeEnum: string
{
    case NONE = "NONE";
    case MOBILE = "MOBILE";
    case EMAIL = "EMAIL";
    case MOBILE_EMAIL = "MOBILE_EMAIL";
}
