<?php

namespace Ako\Zarinpal\Php\Contracts;

enum ReconcileWageTypeEnum: string
{
    case NORMAL = "NORMAL";
    case FLOAT = "FLOAT";
    case FIX = "FIX";
    case OFFLINE = "OFFLINE";
    case OFFLINE_STATIC = "OFFLINE_STATIC";
    case DIRECT = "DIRECT";
}
