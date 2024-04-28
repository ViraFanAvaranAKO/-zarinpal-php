<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\ReferrerInvoiceStatusEnum;
use Ako\Zarinpal\Php\Contracts\RefundReasonEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class Refund extends BaseModel
{
    public static function getRelations(): array
    {
        return [
            'instant_payout' => Relation::hasOne(InstantPayout::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'session_id' => Type::ID(true),
            'reason' => Type::enum(RefundReasonEnum::class),
            'created_at' => Type::datetime(),
            'updated_at' => Type::datetime()
        ];
    }
}
