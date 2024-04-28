<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\InstantPayoutTypeEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class InstantPayoutInquiry extends BaseModel
{
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'fee_percent' => Type::float(),
            'max_amount' => Type::integer(),
            'min_satna' => Type::integer(),
            'fee' => Type::integer(),
            'type' => Type::enum(InstantPayoutTypeEnum::class),
            'fixed_fee_amount' => Type::integer(),
            'max_fee_amount' => Type::integer(),
        ];
    }
}
