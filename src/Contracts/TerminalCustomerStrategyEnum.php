<?php

namespace Ako\Zarinpal\Php\Contracts;

enum TerminalCustomerStrategyEnum: string
{
    case MOBILE = "MOBILE";
    case EMAIL = "EMAIL";
    case DISABLE = "DISABLE";
}
