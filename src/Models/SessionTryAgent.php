<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\ReconciliationStatusEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class SessionTryAgent extends BaseModel
{
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'session_id' => Type::ID(true),
            'ip' => Type::string(),
            'agent' => Type::string(),
            'country_code' => Type::string(),
            'browser' => Type::string(),
            'browser_version' => Type::string(),
            'os' => Type::string(),
            'os_version' => Type::string(),
            'device' => Type::string(),
            'device_type' => Type::string()
        ];
    }
}
