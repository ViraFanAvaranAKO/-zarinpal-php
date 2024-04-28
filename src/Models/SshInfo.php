<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class SshInfo extends BaseModel
{
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'bcn' => Type::string(),
            'bcs' => Type::string(),
            'bcsl' => Type::string(),
            'bcsn' => Type::string(),
        ];
    }
}
