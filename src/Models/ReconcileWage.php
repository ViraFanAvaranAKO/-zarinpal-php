<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\ReconciliationWageTypeEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class ReconcileWage extends BaseModel
{
    public static function getRelations(): array
    {
        return [
            'bank_account' => Relation::hasOne(BankAccount::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::integer(),
            'amount' => Type::integer(),
            'fee' => Type::integer(),
            'shaparak_tracking_number' => Type::string(),
            'type' => Type::enum(ReconciliationWageTypeEnum::class),
            'type_id' => Type::string(),
            'shaparak_error_type' => Type::string(),
            'shaparak_error_message' => Type::string(),
            'returned_at' => Type::datetime(),
        ];
    }
}
