<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class BankAccount extends BaseModel
{
    use IsSelectable, HasQueries;

    public static function getRelations(): array
    {
        return [
            'issuing_bank' => Relation::hasOne(IssuingBank::class)
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'iban' => Type::string(),
            'status' => Type::enum(BankAccountStatusEnum::class),
            'type' => Type::enum(BankAccountTypeEnum::class),
            'is_legal' => Type::bool(),
            'expired_at' => Type::date(),
            'name' => Type::string(),
            'holder_name' => Type::string(),
            'created_at' => Type::datetime(),
            'updated_at' => Type::datetime(),
            'deleted_at' => Type::datetime(),
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "BankAccounts";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'id' => Type::ID(),
            'status' => Type::enum(BankAccountStatusEnum::class),
            'type' => Type::enum(BankAccountTypeEnum::class),
            'iban' => Type::string(),
            'holder_name' => Type::string(),
            'iban_holder_name' => Type::string(),
            'offset' => Type::integer(),
            'limit' => Type::integer(),
        ];
    }
    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "BankAccountAdd",
                'as' => "insert",
                'arguments' => [
                    'iban' => Type::string(true),
                    'is_legal' => Type::bool(true),
                    'name' => Type::string(),
                    'type' => Type::enum(BankAccountTypeEnum::class),
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "BankAccountEdit",
                'as' => "update",
                'arguments' => [
                    'id' => Type::ID(true),
                    'iban' => Type::string(),
                    'is_legal' => Type::bool(),
                    'name' => Type::string(),
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "BankAccountByCardAdd",
                'as' => "insertByCard",
                'arguments' => [
                    'pan' => Type::string(true),
                    'is_legal' => Type::bool(true),
                    'name' => Type::string(),
                    'type' => Type::enum(BankAccountTypeEnum::class)
                ],
                'return_type' => Type::model(self::class)
            ]
        ];
    }
}
