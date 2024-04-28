<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\InstantPayoutActionTypeEnum;
use Ako\Zarinpal\Php\Contracts\ReferrerInvoiceStatusEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLQuery;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class RefundFeeInquiry extends BaseModel
{
    use HasQueries;
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'base_fee' => Type::integer(),
            'step_amount' => Type::integer(),
            'step_fee' => Type::integer(),
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLQuery::class,
                'operation' => "RefundFeeInquiry",
                'as' => "inquire",
                'arguments' => [
                    'method' => Type::enum(InstantPayoutActionTypeEnum::class)
                ],
                'return_type' => Type::model(self::class)
            ]
        ];
    }
}
