<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\ReferrerInvoiceStatusEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLQuery;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class Referrer extends BaseModel
{
    use IsSelectable, HasQueries;
    public static function getRelations(): array
    {
        return [
            'user' => Relation::hasMany(UserReferred::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'all_referrers' => Type::integer(true),
            'sum_referrers' => Type::integer(true),
            'month_active_referrers' => Type::integer(true),
            'sum_commission' => Type::integer(true)
        ];
    }
    public static function getSelectOperation(): string
    {
        return static::$select_operation = "Referrers";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'from_date' => Type::datetime(),
            'to_date' => Type::datetime()
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLQuery::class,
                'operation' => "ReferrersReport",
                'as' => "report",
                'arguments' => [
                    'offset' => Type::integer(),
                    'limit' => Type::integer()
                ],
                'return_type' => Type::model(self::class)
            ]
        ];
    }
}
