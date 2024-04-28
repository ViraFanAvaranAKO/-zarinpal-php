<?php

namespace Ako\Zarinpal\Php\Contracts;

enum ZarinLinkRequiredFieldsStatusEnum: string
{
    case REQUIRED = "REQUIRED";
    case OPTIONAL = "OPTIONAL";
    case HIDDEN = "HIDDEN";
}
