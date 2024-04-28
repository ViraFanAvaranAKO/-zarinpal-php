<?php

namespace Ako\Zarinpal\Php\Contracts;

enum NotificationPreferenceChannelEnum: string
{
    case MAIL = "MAIL";
    case SMS = "SMS";
    case PUSH = "PUSH";
    case WEBHOOK = "WEBHOOK";
    case TELEGRAM = "TELEGRAM";
}
