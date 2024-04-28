<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class ForeignNational extends BaseModel
{
    use HasQueries;
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'foreign_pervasive_code' => Type::string(),
            'passport_number' => Type::string(),
            'passport_expire_date' => Type::date(),
            'country_code' => Type::string(),
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "ForeignNationalAdd",
                'as' => "insert",
                'arguments' => [
                    'foreign_pervasive_code' => Type::string(true),
                    'passport_number' => Type::string(true),
                    'passport_expire_date' => Type::date(),
                    'country_code' => Type::string(true)
                ],
                'return_type' => Type::model(self::class)
            ]
        ];
    }
}
