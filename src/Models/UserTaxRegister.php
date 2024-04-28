<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\TaxTypeEnum;
use Ako\Zarinpal\Php\Contracts\UserLevelEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class UserTaxRegister extends BaseModel
{
    use HasQueries;
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'trackingNumber' => Type::string(),
            'followCode' => Type::string(),
            'shahkarValid' => Type::string(),
            'tprID' => Type::string(),
            'errorMessage' => Type::string(),
            'errorStatus' => Type::string()
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "CheckTax",
                'as' => "checkTax",
                'arguments' => [
                    'type' => Type::enum(TaxTypeEnum::class),
                    'address_id' => Type::ID()
                ],
                'return_type' => Type::model(self::class)
            ]
        ];
    }
}
