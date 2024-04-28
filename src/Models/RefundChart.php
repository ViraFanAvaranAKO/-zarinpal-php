<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\IntervalTypeEnum;
use Ako\Zarinpal\Php\Contracts\ReferrerInvoiceStatusEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class RefundChart extends BaseModel
{
    use IsSelectable;
    public static function getEagerLoads(): array
    {
        return [
            'refunds', 'total'
        ];
    }
    public static function getRelations(): array
    {
        return [
            'refunds' => Relation::hasMany(AnalysisDetails::class),
            'total' => Relation::hasOne(RefundTotal::class),
        ];
    }
    public static function getFields(): array
    {
        return [];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "RefundChart";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'terminal_id' => Type::ID(true),
            'to_date' => Type::date(),
            'from_date' => Type::date(),
            'interval' => Type::enum(IntervalTypeEnum::class)
        ];
    }
}
