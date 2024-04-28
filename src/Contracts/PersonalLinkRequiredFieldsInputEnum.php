<?php

namespace Ako\Zarinpal\Php\Contracts;

enum PersonalLinkRequiredFieldsInputEnum: string
{
    case NAME = "NAME";
    case EMAIL = "EMAIL";
    case MOBILE = "MOBILE";
    case ADDRESS = "ADDRESS";
    case LANDLINE = "LANDLINE";
    case DESCRIPTION = "DESCRIPTION";
}
