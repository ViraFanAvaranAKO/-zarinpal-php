<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\PredefineResponseModelTypeEnum;
use Ako\Zarinpal\Php\Helpers\Type;

class PredefinedResponse extends BaseModel
{
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'title' => Type::string(),
            'message' => Type::string(),
            'model_type' => Type::enum(PredefineResponseModelTypeEnum::class),
        ];
    }
}
