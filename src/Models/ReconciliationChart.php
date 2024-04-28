<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\IntervalTypeEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class ReconciliationChart extends BaseModel
{
    use IsSelectable;
    public static function getEagerLoads(): array
    {
        return [
            'normalReconciliations', 'payouts', 'instantPayouts', 'refunds',
        ];
    }
    public static function getRelations(): array
    {
        return [
            'normalReconciliations' => Relation::hasMany(AnalysisDetails::class),
            'payouts' => Relation::hasMany(AnalysisDetails::class),
            'instantPayouts' => Relation::hasMany(AnalysisDetails::class),
            'refunds' => Relation::hasMany(AnalysisDetails::class),
        ];
    }
    public static function getFields(): array
    {
        return [];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "ReconciliationChart";
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
