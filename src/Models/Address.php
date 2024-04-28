<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\AddressTypeEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class Address extends BaseModel
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
            'type' => Type::enum(AddressTypeEnum::class),
            'title' => Type::string(),
            'address' => Type::string(),
            'landline' => Type::string(),
            'postal_code' => Type::string(),
            'tax_id' => Type::string(),
            'location' => Type::string(),
            'is_postal_code_verified' => Type::bool(),
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "Addresses";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "AddressAdd",
                'as' => "insert",
                'arguments' => [
                    'type' => Type::enum(AddressTypeEnum::class),
                    'address' => Type::string(true),
                    'title' => Type::string(),
                    'landline' => Type::string(true),
                    'postal_code' => Type::string(true),
                    'location' => Type::string(),
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "AddressEdit",
                'as' => "update",
                'arguments' => [
                    'id' => Type::ID(true),
                    'type' => Type::enum(AddressTypeEnum::class),
                    'address' => Type::string(true),
                    'title' => Type::string(),
                    'landline' => Type::string(true),
                    'postal_code' => Type::string(true),
                    'location' => Type::string(),
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "AddressRemove",
                'as' => "delete",
                'arguments' => [
                    'id' => Type::ID(true)
                ],
                'return_type' => Type::bool()
            ]
        ];
    }
}
