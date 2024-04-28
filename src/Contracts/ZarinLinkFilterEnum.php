<?php

namespace Ako\Zarinpal\Php\Contracts;

enum ZarinLinkFilterEnum: string
{
    case ALL = "ALL";
    case ACTIVE = "ACTIVE";
    case TRASH = "TRASH";
}
