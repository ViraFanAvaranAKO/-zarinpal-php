<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Contracts\NotifyTypeEnum;
use Ako\Zarinpal\Php\Contracts\SessionFeeTypeEnum;
use Ako\Zarinpal\Php\Contracts\TerminalFeeTypeEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class Invoice extends BaseModel
{
    use IsSelectable, HasQueries;
    public static function getRelations(): array
    {
        return [
            'sessions' => Relation::hasMany(Session::class),
            'notes' => Relation::hasMany(Note::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'terminal_id' => Type::ID(true),
            'amount' => Type::integer(),
            'fee' => Type::integer(),
            'description' => Type::string(),
            'note' => Type::string(),
            'status' => Type::enum(InvoiceStatusEnum::class),
            'fee_type' => Type::enum(SessionFeeTypeEnum::class),
            'payer_name' => Type::string(),
            'payer_mobile' => Type::string(),
            'payer_email' => Type::string(),
            'notify_type' => Type::enum(NotifyTypeEnum::class),
            'created_at' => Type::datetime(),
            'updated_at' => Type::datetime(),
            'deleted_at' => Type::datetime(),
        ];
    }
    public static function getSelectOperation(): string
    {
        return static::$select_operation = "Invoice";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'id' => Type::ID(),
            'terminal_id' => Type::ID(true),
            'status' => Type::enum(InvoiceStatusEnum::class),
            'created_from_date' => Type::datetime(),
            'created_to_date' => Type::datetime(),
            'amount' => Type::integer(),
            'description' => Type::string(),
            'payer_name' => Type::string(),
            'payer_mobile' => Type::string(),
            'payer_email' => Type::string(),
            'min_amount' => Type::integer(),
            'max_amount' => Type::integer(),
            'offset' => Type::integer(),
            'limit' => Type::integer()
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "InvoiceAdd",
                'as' => "insert",
                'arguments' => [
                    'terminal_id' => Type::ID(),
                    'merchant_id' => Type::string(),
                    'amount' => Type::integer(true),
                    'fee_type' => Type::enum(TerminalFeeTypeEnum::class),
                    'description' => Type::string(true),
                    'note' => Type::string(),
                    'callback_url' => Type::string(),
                    'payer_name' => Type::string(true),
                    'payer_mobile' => Type::string(true),
                    'payer_email' => Type::string(),
                    'count' => Type::integer(),
                    'notify_type' => Type::enum(NotifyTypeEnum::class)
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "InvoiceEdit",
                'as' => "update",
                'arguments' => [
                    'id' => Type::string(true),
                    'description' => Type::string(true),
                    'note' => Type::string(),
                    'payer_name' => Type::string(true),
                    'payer_mobile' => Type::string(true),
                    'payer_email' => Type::string(),
                    'notify_type' => Type::enum(NotifyTypeEnum::class)
                ],
                'return_type' => Type::model(self::class)
            ]
        ];
    }
}
