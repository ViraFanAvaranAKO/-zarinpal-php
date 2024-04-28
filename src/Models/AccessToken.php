<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;
use Ako\Zarinpal\Php\Traits\IsSelectable;

class AccessToken extends BaseModel
{
    use IsSelectable, HasQueries;
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'application' => Type::string(),
            'platform' => Type::string(),
            'stable_version' => Type::integer(),
            'stable_url' => Type::string(),
            'latest_version' => Type::integer(),
            'latest_url' => Type::string()
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "AccessTokens";
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
                'operation' => "AccessTokenRemove",
                'as' => "delete",
                'arguments' => [
                    'token_id' => Type::ID(true)
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "PersonalAccessToken",
                'as' => "personalToken",
                'arguments' => [
                    'token_name' => Type::string(true)
                ],
                'return_type' => Type::model(self::class)
            ]
        ];
    }
}
