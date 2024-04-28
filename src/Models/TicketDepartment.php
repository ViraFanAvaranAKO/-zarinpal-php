<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class TicketDepartment extends BaseModel
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
            'title' => Type::string(),
            'user_access' => Type::bool(),
            'weight' => Type::integer()
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "TicketDepartments";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [];
    }
}
