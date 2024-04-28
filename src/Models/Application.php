<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\AccessTokenEnum;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\IsUpdatable;

class Application extends BaseModel
{
    use IsSelectable;

    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'personal_token' => Type::string(),
            'client_name' => Type::string(true),
            'client_fa_name' => Type::string(true),
            'client_id' => Type::string(true),
            'client_type' => Type::enum(AccessTokenEnum::class),
            'name' => Type::string(),
            'avatar' => Type::string(),
            'terminal_domain' => Type::string(),
            'login_ip' => Type::string(),
            'country' => Type::string(),
            'user_agent' => Type::string(),
            'country_code' => Type::string(),
            'revoked' => Type::bool(),
            'current' => Type::bool(),
            'personal' => Type::bool(),
            'expires_at' => Type::datetime(),
            'updated_at' => Type::datetime(),
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "Application";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [];
    }
}
