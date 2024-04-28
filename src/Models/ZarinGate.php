<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Contracts\PaymentTerminalStatusEnum;
use Ako\Zarinpal\Php\Contracts\TerminalStatusEnum;
use Ako\Zarinpal\Php\Contracts\TicketReplyFeedbackTypeEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class ZarinGate extends BaseModel
{
    public static function getRelations(): array
    {
        return [
            'payment' => Relation::hasOne(Payment::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'psp' => Type::string(),
            'status' => Type::enum(PaymentTerminalStatusEnum::class),
            'system_status' => Type::enum(TerminalStatusEnum::class),
            'merchant_id' => Type::string(),
            'terminal_id' => Type::string(),
            'dont_send_tax' => Type::bool(),
            'deleted_at' => Type::datetime(),
            'error' => Type::list(Type::string())
        ];
    }
}
