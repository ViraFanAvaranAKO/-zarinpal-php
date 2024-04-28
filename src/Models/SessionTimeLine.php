<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\ReconciliationStatusEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class SessionTimeLine extends BaseModel
{
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'created_time' => Type::datetime(),
            'created_reference' => Type::datetime(),
            'canceled_time' => Type::datetime(),
            'in_bank_time' => Type::datetime(),
            'in_bank_name' => Type::string(),
            'settled_time' => Type::datetime(),
            'verified_time' => Type::datetime(),
            'verified_reference' => Type::datetime(),
            'reconciled_time' => Type::datetime(),
            'reconciled_id' => Type::integer(),
            'refund_amount' => Type::integer(),
            'refund_time' => Type::datetime(),
            'refund_status' => Type::enum(ReconciliationStatusEnum::class),
            'refund_id' => Type::integer(),
            'refund_reference_id' => Type::string(),
            'disputed_time' => Type::datetime(),
            'disputed_session_id' => Type::integer(),
            'disputed_by' => Type::string(),
            'disputed_description' => Type::string(),
            'disputed_resolve_time' => Type::datetime(),
            'disputed_resolve_description' => Type::datetime(),
            'disputed_resolve_session_id' => Type::integer(),
            'disputed_resolve_by' => Type::string()
        ];
    }
}
