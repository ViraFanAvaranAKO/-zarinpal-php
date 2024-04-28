<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\TerminalFeeTypeEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class TerminalCustomer extends BaseModel
{
    use IsSelectable;
    public static function getRelations(): array
    {
        return [
            'notes' => Relation::hasMany(Note::class),
            'sessions' => Relation::hasMany(Session::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(),
            'name' => Type::string(),
            'email' => Type::string(),
            'mobile' => Type::string(),
            'custom_id' => Type::ID(),
            'method' => Type::enum(TerminalFeeTypeEnum::class),
            'sessions_count' => Type::integer(),
            'sessions_sum' => Type::integer(),
            'first_session_date' => Type::datetime(),
            'last_session_date' => Type::datetime(),
            'created_at' => Type::datetime()
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "TerminalCustomer";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'id' => Type::ID(),
            'terminal_id' => Type::ID(),
            'name' => Type::integer(),
            'created_from_date' => Type::datetime(),
            'created_to_date' => Type::datetime(),
            'note' => Type::string(),
            'offset' => Type::integer(),
            'limit' => Type::integer(),
            'sessions_limit' => Type::integer(),
            'sessions_offset' => Type::integer()
        ];
    }
}
