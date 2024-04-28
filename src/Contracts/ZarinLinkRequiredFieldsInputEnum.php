<?php

namespace Ako\Zarinpal\Php\Contracts;

enum ZarinLinkRequiredFieldsInputEnum: string
{
    case NAME = "NAME";
    case EMAIL = "EMAIL";
    case MOBILE = "MOBILE";
    case DESCRIPTION = "DESCRIPTION";
    case CUSTOM_FIELD_1 = "CUSTOM_FIELD_1";
    case CUSTOM_FIELD_2 = "CUSTOM_FIELD_2";
}
