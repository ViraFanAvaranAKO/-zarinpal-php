<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\CardStatusEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;

class Card extends BaseModel
{
    public static function getRelations(): array
    {
        return [
            'issuing_bank' => Relation::hasOne(IssuingBank::class)
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'user_id' => Type::ID(),
            'pan' => Type::string(),
            'status' => Type::enum(CardStatusEnum::class),
            'expired_at' => Type::date(),
            'created_at' => Type::datetime(),
            'updated_at' => Type::datetime(),
            'deleted_at' => Type::datetime(),
        ];
    }
}
