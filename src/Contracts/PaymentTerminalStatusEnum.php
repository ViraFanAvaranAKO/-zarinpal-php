<?php

namespace Ako\Zarinpal\Php\Contracts;

enum PaymentTerminalStatusEnum: string
{
    case Active = "Active";
    case DeActive = "DeActive";
    case Pending = "Pending";
    case InProgressPsp = "InProgressPsp";
    case InProgressShaparak = "InProgressShaparak";
    case Reject = "Reject";
    case InProgressShaparakActive = "InProgressShaparakActive";
    case NewPasswordFailed = "NewPasswordFailed";
    case FollowupPspError = "FollowupPspError";
    case InProgressShaparakError = "InProgressShaparakError";
    case InProgressDefine = "InProgressDefine";
    case InProgressDefineError = "InProgressDefineError";
}
