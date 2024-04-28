<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\FeeGroupTypeEnum;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;

class FeeGroup extends BaseModel
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
            'name' => Type::string(),
            'slug' => Type::string(),
            'cycle' => Type::string(),
            'percent' => Type::float(),
            'type' => Type::enum(FeeGroupTypeEnum::class),
            'fix_fee_amount' => Type::float(),
            'discount_percent' => Type::float(),
            'zarincard_percent' => Type::float(),
            'max_fee_amount' => Type::integer(),
            'expire_in' => Type::integer(),
            'is_manual' => Type::bool(),
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "TerminalFeeGroups";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [];
    }
}
