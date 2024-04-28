<?php

namespace Ako\Zarinpal\Php\Contracts;

enum NotificationPreferenceTypeEnum: string
{
    case TICKET_NEW = "TICKET_NEW";
    case TICKET_REPLY = "TICKET_REPLY";
    case NEW_SESSION = "NEW_SESSION";
    case SESSION_SUCCESS = "SESSION_SUCCESS";
    case RECONCILE = "RECONCILE";
}
