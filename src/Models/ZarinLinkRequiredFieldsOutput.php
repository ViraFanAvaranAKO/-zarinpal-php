<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\ZarinLinkRequiredFieldsStatusEnum;
use Ako\Zarinpal\Php\Contracts\ZarinLinkRequiredFieldsInputEnum;
use Ako\Zarinpal\Php\Contracts\UserVerificationTypeEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class ZarinLinkRequiredFieldsOutput extends BaseModel
{
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'input' => Type::enum(ZarinLinkRequiredFieldsInputEnum::class),
            'status' => Type::enum(ZarinLinkRequiredFieldsStatusEnum::class),
            'placeholder' => Type::string()
        ];
    }
}
