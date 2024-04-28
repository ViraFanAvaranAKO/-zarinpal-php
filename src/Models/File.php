<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\FeeGroupTypeEnum;
use Ako\Zarinpal\Php\Helpers\Type;

class File extends BaseModel
{
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::string(true),
            'url' => Type::string(),
        ];
    }
}
