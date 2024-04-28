<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class TerminalCategory extends BaseModel
{
    use IsSelectable;
    public static function getRelations(): array
    {
        return [
            'children' => Relation::hasMany(TerminalCategory::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'parent_id' => Type::ID(),
            'title' => Type::string(),
            'slug' => Type::string(),
            'category_identifier' => Type::ID()
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "TerminalCategories";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [];
    }
}
