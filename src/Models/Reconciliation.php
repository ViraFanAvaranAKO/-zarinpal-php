<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\FilterEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Contracts\OrderEnum;
use Ako\Zarinpal\Php\Contracts\ReconciliationStatusEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class Reconciliation extends BaseModel
{
    use IsSelectable, HasQueries;
    public static function getRelations(): array
    {
        return [
            'wages' => Relation::hasMany(ReconcileWage::class),
            'payment' => Relation::hasOne(Payment::class),
            'terminal' => Relation::hasOne(Terminal::class),
            'notes' => Relation::hasMany(Note::class),
            'sessions' => Relation::hasMany(Session::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'terminal_id' => Type::ID(),
            'status' => Type::enum(ReconciliationStatusEnum::class),
            'amount' => Type::integer(),
            'fee' => Type::integer(),
            'shaparak_fee' => Type::integer(),
            'description' => Type::string(),
            'note' => Type::string(),
            'reference_id' => Type::string(),
            'sessions_count' => Type::integer(),
            'wages_count' => Type::integer(),
            'payable_at' => Type::datetime(),
            'estimated_payed_at' => Type::datetime(),
            'reconciled_at' => Type::datetime(),
            'created_at' => Type::datetime(),
        ];
    }


    public static function getSelectOperation(): string
    {
        return static::$select_operation = "Reconciliation";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'id' => Type::ID(),
            'terminal_id' => Type::ID(),
            'reference_id' => Type::string(),
            'filter' => Type::enum(ReconciliationStatusEnum::class),
            'except' => Type::list(Type::enum(ReconciliationStatusEnum::class)),
            'order' => Type::enum(OrderEnum::class),
            'amount' => Type::integer(),
            'min_amount' => Type::integer(),
            'max_amount' => Type::integer(),
            'note' => Type::string(),
            'text' => Type::string(),
            'created_from_date' => Type::datetime(),
            'created_to_date' => Type::datetime(),
            'offset' => Type::integer(),
            'limit' => Type::integer(),
            'sessions_limit' => Type::integer(),
            'sessions_offset' => Type::integer(),
            'wages_limit' => Type::integer(),
            'wages_offset' => Type::integer()
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "ReconciliationExcelExport",
                'as' => "export",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'created_from_date' => Type::datetime(),
                    'created_to_date' => Type::datetime(),
                    'filter' => Type::enum(FilterEnum::class),
                    'amount' => Type::integer(),
                    'min_amount' => Type::integer(),
                    'max_amount' => Type::integer(),
                    'name' => Type::string()
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "IbanReconciliationExcelExport",
                'as' => "exportIban",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'reconcile_id' => Type::ID(true),
                    'name' => Type::string()
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "ReconciliationModifyNote",
                'as' => "updateNote",
                'arguments' => [
                    'reconciliation_id' => Type::ID(true),
                    'note' => Type::string()
                ],
                'return_type' => Type::model(self::class)
            ]
        ];
    }
}
