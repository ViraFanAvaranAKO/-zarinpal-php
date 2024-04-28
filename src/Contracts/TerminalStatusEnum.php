<?php

namespace Ako\Zarinpal\Php\Contracts;

enum TerminalStatusEnum: string
{
    case REJECTED = "REJECTED";
    case SUSPEND = "SUSPEND";
    case INACTIVE = "INACTIVE";
    case ACTIVE = "ACTIVE";
    case PENDING = "PENDING";
    case PENDING_SHAPARAK = "PENDING_SHAPARAK";
    case REJECTED_SHAPARAK = "REJECTED_SHAPARAK";
}
