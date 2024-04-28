<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\SessionTypeEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\FilterEnum;
use Ako\Zarinpal\Php\Contracts\InstantPayoutActionTypeEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Contracts\OrderEnum;
use Ako\Zarinpal\Php\Contracts\ReconciliationStatusEnum;
use Ako\Zarinpal\Php\Contracts\RefundReasonEnum;
use Ako\Zarinpal\Php\Contracts\SessionFeeTypeEnum;
use Ako\Zarinpal\Php\Contracts\SessionStatusEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\GraphQLQuery;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class Session extends BaseModel
{
    use IsSelectable, HasQueries;
    public static function getRelations(): array
    {
        return [
            'session_tries' => Relation::hasMany(SessionTry::class),
            'timeline' => Relation::hasOne(SessionTimeLine::class),
            'notes' => Relation::hasMany(Note::class),
            'payer_info' => Relation::hasOne(SessionPayerInfo::class),
            'coupon_session' => Relation::hasOne(CouponSession::class),
            'coupon' => Relation::hasOne(Coupon::class),
            'refund' => Relation::hasOne(Refund::class),
            'terminal' => Relation::hasOne(Terminal::class),
            'wage_payouts' => Relation::hasMany(Payout::class)
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'authority' => Type::string(),
            'terminal_id' => Type::ID(true),
            'reference_id' => Type::string(),
            'reconciliation_id' => Type::ID(),
            'type' => Type::enum(SessionTypeEnum::class),
            'fee_type' => Type::enum(SessionFeeTypeEnum::class),
            'status' => Type::enum(SessionStatusEnum::class),
            'callback_url' => Type::string(),
            'referer_url' => Type::string(),
            'server_ip' => Type::string(),
            'amount' => Type::integer(),
            'fee' => Type::integer(),
            'shaparak_fee' => Type::integer(),
            'description' => Type::string(),
            'note' => Type::string(),
            'metadata' => Type::string(),
            'wages' => Type::string(),
            'expire_in' => Type::datetime(),
            'payable_at' => Type::datetime(),
            'reconciled_at' => Type::datetime(),
            'created_at' => Type::datetime(),
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "Session";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'id' => Type::ID(),
            'terminal_id' => Type::ID(),
            'reference_id' => Type::string(),
            'authority' => Type::string(),
            'filter' => Type::enum(FilterEnum::class),
            'order' => Type::enum(OrderEnum::class),
            'relation_id' => Type::ID(),
            'reconciliation_id' => Type::ID(),
            'type' => Type::enum(SessionTypeEnum::class),
            'card_pan' => Type::string(),
            'rrn' => Type::string(),
            'description' => Type::string(),
            'mobile' => Type::string(),
            'email' => Type::string(),
            'created_from_date' => Type::datetime(),
            'created_to_date' => Type::datetime(),
            'text' => Type::string(),
            'search' => Type::string(),
            'offset' => Type::integer(),
            'amount' => Type::integer(),
            'min_amount' => Type::integer(),
            'max_amount' => Type::integer(),
            'limit' => Type::integer()
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "AddRefund",
                'as' => "addRefund",
                'arguments' => [
                    'session_id' => Type::ID(true),
                    'amount' => Type::integer(true),
                    'description' => Type::string(),
                    'reason' => Type::enum(RefundReasonEnum::class),
                    'method' => Type::enum(InstantPayoutActionTypeEnum::class)
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "SessionExcelExport",
                'as' => "export",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'created_from_date' => Type::datetime(),
                    'created_to_date' => Type::datetime(),
                    'session_id' => Type::ID(),
                    'reference_id' => Type::string(),
                    'filter' => Type::enum(FilterEnum::class),
                    'type' => Type::enum(SessionTypeEnum::class),
                    'card_pan' => Type::string(),
                    'description' => Type::string(),
                    'mobile' => Type::string(),
                    'email' => Type::string(),
                    'amount' => Type::integer(),
                    'min_amount' => Type::integer(),
                    'max_amount' => Type::integer(),
                    'reconciliation_id' => Type::ID(),
                    'name' => Type::string(),
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "SessionModifyNote",
                'as' => "updateNote",
                'arguments' => [
                    'session_id' => Type::ID(true),
                    'note' => Type::string()
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "SessionCardHolder",
                'as' => "cardHolder",
                'arguments' => [
                    'session_id' => Type::ID(true)
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "SessionCardPan",
                'as' => "cardPan",
                'arguments' => [
                    'session_id' => Type::ID(true)
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "ElasticSession",
                'as' => "getElastic",
                'arguments' => [
                    'text' => Type::string(),
                    'id' => Type::ID(),
                    'card_pan' => Type::string(),
                    'description' => Type::string(),
                    'mobile' => Type::string(),
                    'email' => Type::string(),
                    'amount' => Type::integer(),
                    'rrn' => Type::string()
                ],
                'return_type' => Type::list(Type::model(self::class))
            ]
        ];
    }
}
