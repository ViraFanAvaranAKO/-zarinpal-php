<?php

namespace Ako\Zarinpal\Php\Contracts;

enum UserVerificationTypeEnum: string
{
    case NATIONAL_CARD_DOCUMENTS = "NATIONAL_CARD_DOCUMENTS";
    case SELFIE_DOCUMENTS = "SELFIE_DOCUMENTS";
    case LEGAL_DOCUMENTS = "LEGAL_DOCUMENTS";
    case NATIONAL_CODE = "NATIONAL_CODE";
    case SSN = "SSN";
    case BIRTHDAY = "BIRTHDAY";
    case POSTAL_CODE = "POSTAL_CODE";
    case ADDRESS = "ADDRESS";
    case MAIL = "MAIL";
    case SMS = "SMS";
    case SHAHKAR = "SHAHKAR";
}
