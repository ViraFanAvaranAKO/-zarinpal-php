<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class SessionPayerInfo extends BaseModel
{
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'name' => Type::string(),
            'email' => Type::string(),
            'mobile' => Type::string(),
            'description' => Type::string(),
            'zarin_link_id' => Type::ID(),
            'order_id' => Type::string(),
            'card_holder_name' => Type::string(),
            'card_holder_iban' => Type::string(),
            'card_holder_account_number' => Type::string(),
            'custom_field_1' => Type::string(),
            'custom_field_2' => Type::string()
        ];
    }
}
