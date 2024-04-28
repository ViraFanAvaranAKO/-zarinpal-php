<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\FilterEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Contracts\PayoutStatusEnum;
use Ako\Zarinpal\Php\Contracts\PayoutTypeEnum;
use Ako\Zarinpal\Php\Contracts\ReconciliationPartsEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\GraphQLQuery;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class Payout extends BaseModel
{
    use IsSelectable, HasQueries;
    public static function getRelations(): array
    {
        return [
            'terminal' => Relation::hasOne(Terminal::class),
            'reconciliation' => Relation::hasOne(Reconciliation::class),
            'reference_session' => Relation::hasOne(Session::class),
            'bank_account' => Relation::hasOne(BankAccount::class),
            'notes' => Relation::hasMany(Note::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'status' => Type::enum(PayoutStatusEnum::class),
            'description' => Type::string(),
            'reconciliation_parts' => Type::enum(ReconciliationPartsEnum::class),
            'type' => Type::enum(PayoutTypeEnum::class),
            'amount' => Type::integer(),
            'reached_amount' => Type::integer(),
            'percent' => Type::integer(),
            'priority' => Type::integer(),
            'url_code' => Type::string(),
            'related_payout' => Type::string(),
            'owner_name' => Type::string(),
            'tracking_id' => Type::string(),
            'created_at' => Type::datetime(),
            'updated_at' => Type::datetime(),
            'reconciled_at' => Type::datetime(),
        ];
    }
    public static function getSelectOperation(): string
    {
        return static::$select_operation = "Payout";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'id' => Type::ID(),
            'terminal_id' => Type::ID(),
            'reference_session_id' => Type::ID(),
            'description' => Type::string(),
            'filter' => Type::enum(FilterEnum::class),
            'except' => Type::list(Type::enum(FilterEnum::class, true)),
            'url_code' => Type::string(),
            'amount' => Type::integer(),
            'min_amount' => Type::integer(),
            'max_amount' => Type::integer(),
            'iban' => Type::string(),
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
                'operation' => "PayoutAdd",
                'as' => "insert",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'bank_account_id' => Type::ID(true),
                    'amount' => Type::integer(true),
                    'description' => Type::string(),
                    'reconciliation_parts' => Type::enum(ReconciliationPartsEnum::class)
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "PayoutEdit",
                'as' => "update",
                'arguments' => [
                    'id' => Type::ID(true),
                    'status' => Type::enum(PayoutStatusEnum::class),
                    'priority' => Type::integer()
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "PayoutRetryAdd",
                'as' => "retryInsert",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'bank_account_id' => Type::ID(true),
                    'payout_id' => Type::ID(true)
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "PayoutExcelExport",
                'as' => "export",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'created_from_date' => Type::datetime(),
                    'created_to_date' => Type::datetime(),
                    'min_amount' => Type::integer(),
                    'max_amount' => Type::integer(),
                    'status' => Type::enum(PayoutStatusEnum::class),
                    'name' => Type::string()
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "PayoutBalance",
                'as' => "balance",
                'arguments' => [
                    'terminal_id' => Type::ID(true)
                ],
                'return_type' => Type::model(DayTransactionAvg::class)
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "PayoutInquiry",
                'as' => "inquire",
                'arguments' => [
                    'terminal_id' => Type::ID(true)
                ],
                'return_type' => Type::model(InstantPayoutInquiry::class)
            ]
        ];
    }
}
