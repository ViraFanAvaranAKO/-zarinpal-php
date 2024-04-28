<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class Investment extends BaseModel
{
    use HasQueries;
    public static function getRelations(): array
    {
        return [
            'investment_fund' => Relation::hasOne(InvestmentFund::class),
            'bank_account' => Relation::hasOne(BankAccount::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'terminal_id' => Type::integer(),
            'percent' => Type::integer(),
            'max_limit' => Type::integer(),
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "Investment",
                'as' => "insert",
                'arguments' => [
                    'investment_fund_id' => Type::ID(true),
                    'terminal_id' => Type::ID(true),
                    'percent' => Type::integer(true),
                    'max_limit' => Type::integer(true)
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "RemoveInvestment",
                'as' => "delete",
                'arguments' => [
                    'investment_fund_id' => Type::ID(true),
                    'terminal_id' => Type::ID(true)
                ],
                'return_type' => Type::list(Type::model(self::class))
            ]
        ];
    }
}
