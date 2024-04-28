<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLQuery;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class IBAN extends BaseModel
{
    use HasQueries;
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'iban' => Type::string(true),
            'holder_name' => Type::string(),
            'bank_name' => Type::string()
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLQuery::class,
                'operation' => "CheckCardIBAN",
                'as' => "check",
                'arguments' => [
                    'pan' => Type::string(),
                    'iban' => Type::string()
                ],
                'return_type' => Type::model(self::class)
            ]
        ];
    }
}
