<?php

namespace Ako\Zarinpal\Php\Contracts;

enum PredefineResponseModelTypeEnum: string
{
    case Terminal = "Terminal";
    case Ticket = "Ticket";
    case BankAccount = "BankAccount";
    case User = "User";
}
