<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class Faq extends BaseModel
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
            'question' => Type::string(),
            'answer' => Type::string(),
            'slug' => Type::string(),
            'faq_department_id' => Type::ID(),
        ];
    }


    public static function getSelectOperation(): string
    {
        return static::$select_operation = "Faqs";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'slug' => Type::string()
        ];
    }
}
