<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\ExportStatusEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Contracts\NotifyTypeEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class SessionExport extends BaseModel
{
    use IsSelectable;
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(),
            'type' => Type::string(),
            'name' => Type::string(),
            'url' => Type::string(),
            'size' => Type::integer(),
            'uuid' => Type::string(),
            'status' => Type::enum(ExportStatusEnum::class),
            'created_at' => Type::datetime()
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "SessionExport";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'slug' => Type::string(),
            'offset' => Type::integer(),
            'limit' => Type::integer()
        ];
    }
}
