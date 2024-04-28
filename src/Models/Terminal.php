<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\CardStatusEnum;
use Ako\Zarinpal\Php\Contracts\TerminalFeeTypeEnum;
use Ako\Zarinpal\Php\Contracts\VerifyTypeEnum;
use Ako\Zarinpal\Php\Contracts\ReconcileCycleTypeEnum;
use Ako\Zarinpal\Php\Contracts\ReconcileTypeEnum;
use Ako\Zarinpal\Php\Contracts\ReconcileWageTypeEnum;
use Ako\Zarinpal\Php\Contracts\TerminalChnTypeEnum;
use Ako\Zarinpal\Php\Contracts\TerminalCustomerStrategyEnum;
use Ako\Zarinpal\Php\Contracts\TerminalFlagEnum;
use Ako\Zarinpal\Php\Contracts\TerminalPermissionEnum;
use Ako\Zarinpal\Php\Contracts\TerminalRejectionDueEnum;
use Ako\Zarinpal\Php\Contracts\TerminalStatusEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\GraphQLQuery;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class Terminal extends BaseModel
{
    use IsSelectable, HasQueries;
    public static function getRelations(): array
    {
        return [
            'category' => Relation::hasOne(TerminalCategory::class),
            'address' => Relation::hasOne(Address::class),
            'fee_group' => Relation::hasOne(FeeGroup::class),
            'reconcile_status' => Relation::hasOne(ReconcileStatus::class),
            'notes' => Relation::hasMany(Note::class),
            'required_fields' => Relation::hasMany(PersonalLinkRequiredFieldsOutput::class),
            'reject_reason' => Relation::hasMany(PredefinedResponse::class),
            'psp_priority' => Relation::hasMany(ZarinGate::class),
            'active_psp' => Relation::hasMany(ZarinGate::class),
            'zarin_gate' => Relation::hasMany(ZarinGate::class),
            'income_chart' => Relation::hasMany(IncomeChart::class),
            'today_session' => Relation::hasOne(ChartCountAmount::class),
            'yesterday_session' => Relation::hasOne(ChartCountAmount::class),
            'day_transactions' => Relation::hasOne(DayTransaction::class),
            'enamad_register_history' => Relation::hasOne(EnamadHistory::class),
            'enamad_inquiry_history' => Relation::hasOne(EnamadHistory::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'owner_id' => Type::ID(),
            'mcc_id' => Type::ID(),
            'preferred_bank_account_id' => Type::ID(),
            'domain' => Type::string(),
            'server_ip' => Type::string(),
            'support_phone' => Type::string(),
            'key' => Type::ID(),
            'name' => Type::string(),
            'description' => Type::string(),
            'status' => Type::enum(TerminalStatusEnum::class),
            'flag' => Type::enum(TerminalFlagEnum::class),
            'fee_type' => Type::enum(TerminalFeeTypeEnum::class),
            'chn_type' => Type::enum(TerminalChnTypeEnum::class),
            'customer_strategy' => Type::enum(TerminalCustomerStrategyEnum::class),
            'verify_type' => Type::enum(VerifyTypeEnum::class),
            'logo' => Type::string(),
            'notify_set_reconcile' => Type::bool(),
            'can_payout' => Type::bool(),
            'reconcile_wage_type' => Type::enum(ReconcileWageTypeEnum::class),
            'reconcile_type' => Type::enum(ReconcileTypeEnum::class),
            'reconcile_priority' => Type::enum(ReconcileCycleTypeEnum::class),
            'permissions' => Type::list(Type::enum(TerminalPermissionEnum::class)),
            'is_default' => Type::bool(),
            'pin' => Type::integer(),
            'refund_active' => Type::bool(),
            'created_at' => Type::datetime(),
            'updated_at' => Type::datetime(),
            'deleted_at' => Type::datetime(),
            'rejection_due' => Type::enum(TerminalRejectionDueEnum::class),
            'enamad_expire_date' => Type::date()
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "Terminals";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'id' => Type::ID(),
            'status' => Type::enum(CardStatusEnum::class),
            'offset' => Type::integer(),
            'limit' => Type::integer()
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "TerminalAdd",
                'as' => "insert",
                'arguments' => [
                    'mcc_id' => Type::ID(true),
                    'bank_account_id' => Type::ID(),
                    'domain' => Type::string(true),
                    'support_phone' => Type::string(true),
                    'name' => Type::string(true),
                    'address_id' => Type::ID(),
                    'is_default' => Type::bool(),
                    'logo' => Type::ID(),
                    'description' => Type::string(),
                    'reconcile_priority' => Type::enum(ReconcileCycleTypeEnum::class),
                    'reconcile_wage_type' => Type::enum(ReconcileWageTypeEnum::class)
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "TerminalEdit",
                'as' => "update",
                'arguments' => [
                    'id' => Type::ID(true),
                    'mcc_id' => Type::ID(),
                    'name' => Type::string(),
                    'description' => Type::string(),
                    'logo' => Type::ID(),
                    'server_ip' => Type::string(),
                    'fee_type' => Type::enum(TerminalFeeTypeEnum::class),
                    'chn_type' => Type::enum(TerminalChnTypeEnum::class),
                    'customer_strategy' => Type::enum(TerminalCustomerStrategyEnum::class),
                    'is_default' => Type::bool(),
                    'address_id' => Type::ID(),
                    'support_phone' => Type::string(),
                    'bank_account_id' => Type::ID(),
                    'reconcile_priority' => Type::enum(ReconcileCycleTypeEnum::class),
                    'reconcile_wage_type' => Type::enum(ReconcileWageTypeEnum::class),
                    'psp_priority' => Type::list(Type::string()),
                    'webhook_url' => Type::string()
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "TerminalRemove",
                'as' => "delete",
                'arguments' => [
                    'id' => Type::ID(true)
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "PersonalLinkRequest",
                'as' => "requestPersonalLink",
                'arguments' => [
                    'username' => Type::string(true),
                    'bank_account_id' => Type::ID()
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "PersonalLinkEdit",
                'as' => "editPersonalLink",
                'arguments' => [
                    'required_fields' => Type::list(Type::model(PersonalLinkRequiredFieldsInput::class))
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "PinTerminal",
                'as' => "pin",
                'arguments' => [
                    'id' => Type::ID(true),
                    'pin' => Type::bool(true),
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "EnamadRegisterPayment",
                'as' => "registerEnamadPayment",
                'arguments' => [
                    'terminal_id' => Type::ID()
                ],
                'return_type' => Type::model(EnamadPayment::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "EnamadInquiry",
                'as' => "inquireEnamad",
                'arguments' => [
                    'terminal_id' => Type::ID(true)
                ],
                'return_type' => Type::model(EnamadHistory::class)
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "DailyReport",
                'as' => "dailyReport",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'from_date' => Type::date(true),
                    'to_date' => Type::date(true)
                ],
                'return_type' => Type::model(DailyReport::class)
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "DayTransactions",
                'as' => "dayTransactions",
                'arguments' => [
                    'terminal_id' => Type::ID(true)
                ],
                'return_type' => Type::model(DayTransaction::class)
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "ReconciliationCountChart",
                'as' => "reconciliationCountChart",
                'arguments' => [
                    'terminal_id' => Type::ID(true)
                ],
                'return_type' => Type::model(ChartCountAmount::class)
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "TodaySessionChart",
                'as' => "todaySessionChart",
                'arguments' => [
                    'terminal_id' => Type::ID(true)
                ],
                'return_type' => Type::model(ChartCountAmount::class)
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "YesterdaySessionChart",
                'as' => "yesterdaySessionChart",
                'arguments' => [
                    'terminal_id' => Type::ID(true)
                ],
                'return_type' => Type::model(ChartCountAmount::class)
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "TwoDayChart",
                'as' => "twoDayChart",
                'arguments' => [
                    'terminal_id' => Type::ID(true),
                    'date' => Type::date(true)
                ],
                'return_type' => Type::model(TwoDayChart::class)
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "TerminalDomainCheck",
                'as' => "checkDomain",
                'arguments' => [
                    'domain' => Type::string(true)
                ],
                'return_type' => Type::model(TerminalDomain::class)
            ]
        ];
    }
}
