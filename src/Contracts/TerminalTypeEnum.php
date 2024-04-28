<?php

namespace Ako\Zarinpal\Php\Contracts;

enum TerminalTypeEnum: string
{
    case BLACK_LIST = "BLACK_LIST";
    case WHITE_LIST = "WHITE_LIST";
    case APPROVE_LIST = "APPROVE_LIST";
    case MODIFY_LIST = "MODIFY_LIST";
    case SOCIAL_LIST = "SOCIAL_LIST";
}
