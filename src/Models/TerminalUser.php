<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\TerminalPermissionEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class TerminalUser extends BaseModel
{
    use IsSelectable, HasQueries;
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(),
            'user_id' => Type::ID(),
            'user_name' => Type::string(),
            'user_avatar' => Type::string(),
            'terminal_id' => Type::ID(),
            'access_list' => Type::list(Type::enum(TerminalPermissionEnum::class))
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "TerminalUser";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'terminal_id' => Type::ID(true)
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "TerminalAssignAccess",
                'as' => "addAccess",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'user_id' => Type::ID(true),
                    'access_list' => Type::list(Type::enum(TerminalPermissionEnum::class), true)
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "TerminalDeleteAccess",
                'as' => "deleteAccess",
                'arguments' => [
                    'id' => Type::ID(true),
                    'terminal_id' => Type::ID(true)
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "TerminalEditAccess",
                'as' => "updateAccess",
                'arguments' => [
                    'id' => Type::ID(true),
                    'terminal_id' => Type::ID(true),
                    'access_list' => Type::list(Type::enum(TerminalPermissionEnum::class), true)
                ],
                'return_type' => Type::model(self::class)
            ],
        ];
    }
}
