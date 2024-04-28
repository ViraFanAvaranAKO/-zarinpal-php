<?php

namespace Ako\Zarinpal\Php\Contracts;

enum TicketStatusEnum: string
{
    case ALL = "ALL";
    case NEW = "NEW";
    case IN_PROGRESS = "IN_PROGRESS";
    case SUPPORT_RESPONSE = "SUPPORT_RESPONSE";
    case CLOSED = "CLOSED";
}
