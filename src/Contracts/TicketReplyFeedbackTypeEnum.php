<?php

namespace Ako\Zarinpal\Php\Contracts;

enum TicketReplyFeedbackTypeEnum: string
{
    case INSUFFICIENT = "INSUFFICIENT";
    case DISRESPECTFUL = "DISRESPECTFUL";
    case IRRELEVANT = "IRRELEVANT";
    case HELPFUL = "HELPFUL";
    case NONE = "NONE";
}
