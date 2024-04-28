<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\TerminalTypeEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Contracts\TerminalCmsEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class TerminalDomain extends BaseModel
{
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'type' => Type::enum(TerminalTypeEnum::class),
            'cms' => Type::enum(TerminalCmsEnum::class),
            'title' => Type::string(),
            'description' => Type::string()
        ];
    }
}
