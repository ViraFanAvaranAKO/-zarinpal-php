<?php

namespace Ako\Zarinpal\Php\Contracts;

enum TicketFeedbackTypeEnum: string
{
    case HAPPY = "HAPPY";
    case NEUTRAL = "NEUTRAL";
    case SAD = "SAD";
    case NONE = "NONE";
    case POOR = "POOR";
    case GOOD = "GOOD";
    case PERFECT = "PERFECT";
}
