<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\FilterEnum;
use Ako\Zarinpal\Php\Contracts\InstantPayoutMethodEnum;
use Ako\Zarinpal\Php\Contracts\InstantPayoutStatusEnum;
use Ako\Zarinpal\Php\Contracts\ReconciliationStatusEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\GraphQLQuery;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class InstantPayout extends BaseModel
{
    use IsSelectable, HasQueries;
    public static function getRelations(): array
    {
        return [
            'terminal' => Relation::hasOne(Terminal::class),
            'bank_account' => Relation::hasOne(BankAccount::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'status' => Type::enum(ReconciliationStatusEnum::class),
            'method' => Type::enum(InstantPayoutMethodEnum::class),
            'description' => Type::string(),
            'amount' => Type::integer(),
            'fee' => Type::integer(),
            'reference_id' => Type::string(),
            'url_code' => Type::string(),
            'shaparak_tracking_number' => Type::string(),
            'reconciled_at' => Type::datetime(),
            'created_at' => Type::datetime(),
            'updated_at' => Type::datetime(),
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "InstantPayout";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'id' => Type::ID(),
            'url_code' => Type::string(),
            'terminal_id' => Type::ID(),
            'filter' => Type::enum(FilterEnum::class),
            'reference_id' => Type::string(),
            'amount' => Type::integer(),
            'min_amount' => Type::integer(),
            'max_amount' => Type::integer(),
            'note' => Type::string(),
            'status' => Type::enum(InstantPayoutStatusEnum::class),
            'created_from_date' => Type::datetime(),
            'created_to_date' => Type::datetime(),
            'text' => Type::string(),
            'offset' => Type::integer(),
            'limit' => Type::integer()
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "InstantPayoutAdd",
                'as' => "insert",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'bank_account_id' => Type::ID(true),
                    'amount' => Type::integer(true),
                    'description' => Type::string()
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "InstantPayoutExcelExport",
                'as' => "export",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'created_from_date' => Type::datetime(),
                    'created_to_date' => Type::datetime(),
                    'filter' => Type::enum(FilterEnum::class),
                    'min_amount' => Type::integer(),
                    'max_amount' => Type::integer(),
                    'amount' => Type::integer(),
                    'status' => Type::enum(InstantPayoutStatusEnum::class),
                    'name' => Type::string()
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "InstantPayoutInquiry",
                'as' => "inquire",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'bank_account_id' => Type::ID(true),
                    'amount' => Type::integer()
                ],
                'return_type' => Type::model(InstantPayoutInquiry::class)
            ]
        ];
    }
}
