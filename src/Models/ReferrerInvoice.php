<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\ReferrerInvoiceStatusEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\GraphQLQuery;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class ReferrerInvoice extends BaseModel
{
    use IsSelectable, HasQueries;
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'status' => Type::enum(ReferrerInvoiceStatusEnum::class),
            'amount' => Type::integer(),
            'reference_id' => Type::string(),
            'from_date' => Type::datetime(),
            'to_date' => Type::datetime(),
            'created_at' => Type::datetime()
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "ReferrerInvoice";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'id' => Type::ID(),
            'reference_id' => Type::string(),
            'from_date' => Type::datetime(),
            'to_date' => Type::datetime(),
            'offset' => Type::integer(),
            'limit' => Type::integer()
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "referrerInvoiceSetIban",
                'as' => "setIban",
                'arguments' => [
                    'bank_account_id' => Type::ID()
                ],
                'return_type' => Type::model(BankAccount::class)
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "ReferrerInvoiceGetIban",
                'as' => "getIban",
                'arguments' => [],
                'return_type' => Type::model(BankAccount::class)
            ]
        ];
    }
}
