<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class DailyReport extends BaseModel
{
    public static function getEagerLoads(): array
    {
        return [
            'sessions', 'reconciliations', 'total'
        ];
    }

    public static function getRelations(): array
    {
        return [
            'sessions' => Relation::hasMany(DailyReportDetails::class),
            'reconciliations' => Relation::hasMany(DailyReportDetails::class),
            'total' => Relation::hasOne(DailyReportTotal::class),
        ];
    }
    public static function getFields(): array
    {
        return [];
    }
}
