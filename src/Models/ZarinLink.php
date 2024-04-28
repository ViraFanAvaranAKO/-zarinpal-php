<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Contracts\TicketReplyFeedbackTypeEnum;
use Ako\Zarinpal\Php\Contracts\ZarinLinkFilterEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\GraphQLQuery;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class ZarinLink extends BaseModel
{
    use IsSelectable, HasQueries;
    public static function getRelations(): array
    {
        return [
            'required_fields' => Relation::hasMany(ZarinLinkRequiredFieldsOutput::class),
            'images' => Relation::hasMany(File::class),
            'notes' => Relation::hasMany(Note::class)
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'terminal_id' => Type::ID(),
            'link' => Type::string(),
            'title' => Type::string(),
            'amount' => Type::integer(),
            'description' => Type::string(),
            'show_receipt' => Type::bool(),
            'is_coupon_active' => Type::bool(),
            'limit' => Type::integer(),
            'form_id' => Type::ID(),
            'successful_redirect_url' => Type::string(),
            'failed_redirect_url' => Type::string(),
            'created_at' => Type::datetime(),
            'updated_at' => Type::datetime(),
            'deleted_at' => Type::datetime()
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "ZarinLinks";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'id' => Type::ID(),
            'terminal_id' => Type::ID(),
            'filter' => Type::enum(ZarinLinkFilterEnum::class),
            'amount' => Type::integer(),
            'description' => Type::string(),
            'title' => Type::string(),
            'text' => Type::string(),
            'successful_redirect_url' => Type::string(),
            'failed_redirect_url' => Type::string(),
            'offset' => Type::integer(),
            'limit' => Type::integer()
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "ZarinLinkAdd",
                'as' => "insert",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'title' => Type::string(true),
                    'amount' => Type::integer(true),
                    'description' => Type::string(true),
                    'show_receipt' => Type::bool(true),
                    'is_coupon_active' => Type::bool(true),
                    'required_fields' => Type::list(Type::model(ZarinLinkRequiredFieldsInput::class)),
                    'form_id' => Type::ID(),
                    'limit' => Type::integer(),
                    'successful_redirect_url' => Type::string(),
                    'failed_redirect_url' => Type::string(),
                    'status' => Type::bool()
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "ZarinLinkEdit",
                'as' => "update",
                'arguments' => [
                    'id' => Type::ID(true),
                    'terminal_id' => Type::ID(),
                    'title' => Type::string(),
                    'amount' => Type::integer(),
                    'description' => Type::string(),
                    'show_receipt' => Type::bool(),
                    'form_id' => Type::ID(),
                    'is_coupon_active' => Type::bool(),
                    'required_fields' => Type::list(Type::model(ZarinLinkRequiredFieldsInput::class)),
                    'limit' => Type::integer(),
                    'successful_redirect_url' => Type::string(),
                    'failed_redirect_url' => Type::string()
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "ZarinLinkRemove",
                'as' => "delete",
                'arguments' => [
                    'id' => Type::ID(true)
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "ZarinLinkAddImage",
                'as' => "addImage",
                'arguments' => [
                    'product_id' => Type::ID(true),
                    'images' => Type::list(Type::string(true), true)
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "ZarinLinkDeleteImage",
                'as' => "deleteImage",
                'arguments' => [
                    'product_id' => Type::ID(true),
                    'image_id' => Type::string(true)
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "ZarinLinkRestore",
                'as' => "restore",
                'arguments' => [
                    'id' => Type::ID(true)
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "PersonalLink",
                'as' => "personalLink",
                'arguments' => [],
                'return_type' => Type::model(self::class)
            ]
        ];
    }
}
