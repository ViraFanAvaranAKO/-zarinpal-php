<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\UserVerificationTypeEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class ZarinCard extends BaseModel
{
    use IsSelectable, HasQueries;
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'branch_id' => Type::integer(),
            'name' => Type::string(),
            'address' => Type::string()
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "ZarinCardBranchList";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'province' => Type::string(true)
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "ZarinCardAdd",
                'as' => "insert",
                'arguments' => [
                    'branch_id' => Type::ID(true)
                ],
                'return_type' => Type::model(ZarinCardAdd::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "ZarinCardVerifyPayment",
                'as' => "verifyPayment",
                'arguments' => [
                    'authority' => Type::string(true),
                    'status' => Type::string(true),
                    'branch_id' => Type::ID(true)
                ],
                'return_type' => Type::model(ZarinCardVerifyPayment::class)
            ]
        ];
    }
}
