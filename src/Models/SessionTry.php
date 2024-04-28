<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Contracts\SessionTryStatusEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class SessionTry extends BaseModel
{
    public static function getRelations(): array
    {
        return [
            'agent' => Relation::hasOne(SessionTryAgent::class),
            'payer_user' => Relation::hasOne(UserPublic::class),
            'card_info' => Relation::hasOne(IssuingBank::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'session_id' => Type::ID(true),
            'payment_id' => Type::ID(),
            'payer_ip' => Type::string(),
            'init_time' => Type::integer(),
            'verify_time' => Type::integer(),
            'status' => Type::enum(SessionTryStatusEnum::class),
            'token' => Type::ID(),
            'rrn' => Type::ID(),
            'card_pan' => Type::string(),
            'is_card_mobile_verified' => Type::bool(),
            'created_at' => Type::datetime()
        ];
    }
}
