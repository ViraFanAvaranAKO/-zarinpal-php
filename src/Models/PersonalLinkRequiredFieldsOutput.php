<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\PersonalLinkRequiredFieldsInputEnum;
use Ako\Zarinpal\Php\Contracts\PersonalLinkRequiredFieldsStatusEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class PersonalLinkRequiredFieldsOutput extends BaseModel
{
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'input' => Type::enum(PersonalLinkRequiredFieldsInputEnum::class),
            'status' => Type::enum(PersonalLinkRequiredFieldsStatusEnum::class),
            'placeholder' => Type::string()
        ];
    }
}
