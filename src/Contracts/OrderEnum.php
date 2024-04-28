<?php

namespace Ako\Zarinpal\Php\Contracts;

enum OrderEnum: string
{
    case CREATED_AT_ASC = "CREATED_AT_ASC";
    case PAYABLE_AT_ASC = "PAYABLE_AT_ASC";
    case RECONCILED_AT_ASC = "RECONCILED_AT_ASC";
    case RECONCILED_AT_DESC = "RECONCILED_AT_DESC";
}
