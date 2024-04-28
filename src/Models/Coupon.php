<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\CouponFilterEnum;
use Ako\Zarinpal\Php\Contracts\CouponStatusEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\GraphQLQuery;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class Coupon extends BaseModel
{
    use IsSelectable, HasQueries;
    public static function getRelations(): array
    {
        return [
            'products' => Relation::hasMany(ZarinLink::class),
            'notes' => Relation::hasMany(Note::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'terminal_id' => Type::ID(true),
            'zarin_link_id' => Type::ID(),
            'status' => Type::enum(CouponStatusEnum::class),
            'limit' => Type::integer(),
            'used' => Type::integer(),
            'min_amount' => Type::integer(true),
            'max_discount_amount' => Type::integer(true),
            'discount_percent' => Type::integer(true),
            'code' => Type::string(true),
            'expired_at' => Type::datetime(),
            'created_at' => Type::datetime(),
            'updated_at' => Type::datetime(),
            'deleted_at' => Type::datetime()
        ];
    }


    public static function getSelectOperation(): string
    {
        return static::$select_operation = "Coupons";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'id' => Type::ID(),
            'terminal_id' => Type::ID(true),
            'zarin_link_id' => Type::ID(),
            'code' => Type::string(),
            'filter' => Type::enum(CouponFilterEnum::class),
            'offset' => Type::integer(),
            'limit' => Type::integer()
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "CouponAdd",
                'as' => "insert",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'zarin_link_id' => Type::ID(),
                    'limit' => Type::integer(),
                    'min_amount' => Type::integer(true),
                    'max_discount_amount' => Type::integer(true),
                    'discount_percent' => Type::integer(true),
                    'code' => Type::string(true),
                    'expired_at' => Type::datetime()
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "CouponEdit",
                'as' => "update",
                'arguments' => [
                    'id' => Type::ID(true),
                    'zarin_link_id' => Type::ID(),
                    'limit' => Type::integer(),
                    'min_amount' => Type::integer(),
                    'max_discount_amount' => Type::integer(),
                    'discount_percent' => Type::integer(),
                    'code' => Type::string(),
                    'expired_at' => Type::datetime()
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "CouponRemove",
                'as' => "delete",
                'arguments' => [
                    'id' => Type::ID(true)
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "CouponAssignProduct",
                'as' => "assignProduct",
                'arguments' => [
                    'id' => Type::ID(true),
                    'product_ids' => Type::list(Type::ID())
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "CouponRestore",
                'as' => "restore",
                'arguments' => [
                    'id' => Type::ID(true)
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "CouponCodeIsAvailable",
                'as' => "checkCodeAvailability",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'code' => Type::string(true)
                ],
                'return_type' => Type::bool()
            ]
        ];
    }
}
