<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class ProductForm extends BaseModel
{
    use HasQueries;
    public static function getRelations(): array
    {
        return [
            'fields' => Relation::hasMany(ZarinLinkRequiredFieldsOutput::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::string(true),
            'terminal_id' => Type::string(),
            'title' => Type::string(),
            'successful_redirect_url' => Type::string(),
            'is_default' => Type::bool(),
            'created_at' => Type::datetime(),
            'updated_at' => Type::datetime(),
            'deleted_at' => Type::datetime(),
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "AddProductForm",
                'as' => "insert",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'title' => Type::string(true),
                    'is_default' => Type::bool(true),
                    'fields' => Type::list(Type::model(ZarinLinkRequiredFieldsInput::class), true)
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "DeleteProductForm",
                'as' => "delete",
                'arguments' => [
                    'id' => Type::ID(true)
                ],
                'return_type' => Type::bool()
            ]
        ];
    }
}
