<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class EnamadHistory extends BaseModel
{
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'result' => Type::string(),
            'result_info' => Type::string(),
            'expire_date' => Type::string(),
            'domain' => Type::string(),
            'star' => Type::string(),
            'message' => Type::string(),
            'same_national' => Type::bool(),
            'created_at' => Type::datetime(),
            'updated_at' => Type::datetime(),
        ];
    }
}
