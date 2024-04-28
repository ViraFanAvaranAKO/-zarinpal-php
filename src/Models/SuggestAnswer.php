<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class SuggestAnswer extends BaseModel
{
    use IsSelectable;
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'relation_key' => Type::ID(),
            'relation_id' => Type::ID(),
            'title' => Type::string(),
            'content' => Type::string(),
            'url' => Type::string()
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "SuggestAnswer";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'title' => Type::string()
        ];
    }
}
