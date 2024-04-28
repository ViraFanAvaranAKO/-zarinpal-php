<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLQuery;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class DayTransactionAvg extends BaseModel
{
    use HasQueries;
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'value' => Type::integer(),
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLQuery::class,
                'operation' => "BalanceChart",
                'as' => "balanceChart",
                'arguments' => [
                    'terminal_id' => Type::ID(true)
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "TodayTransactionAvgChart",
                'as' => "todayChart",
                'arguments' => [
                    'terminal_id' => Type::ID(true)
                ],
                'return_type' => Type::model(self::class)
            ]
        ];
    }
}
