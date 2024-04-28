<?php

namespace Ako\Zarinpal\Php\Contracts;

enum PersonalLinkRequiredFieldsStatusEnum: string
{
    case REQUIRED = "REQUIRED";
    case OPTIONAL = "OPTIONAL";
    case HIDDEN = "HIDDEN";
}
