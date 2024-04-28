<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class IncomeChart extends BaseModel
{
    use IsSelectable;

    public static function getEagerLoads(): array
    {
        return [
            'data'
        ];
    }
    public static function getRelations(): array
    {
        return [
            'data' => Relation::hasMany(IncomeChartDetails::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'key' => Type::string(),
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "IncomeChart";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'terminal_id' => Type::ID(true)
        ];
    }
}
