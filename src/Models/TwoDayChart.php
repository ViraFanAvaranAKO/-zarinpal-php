<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class TwoDayChart extends BaseModel
{
    public static function getEagerLoads(): array
    {
        return [
            'today', 'other_day'
        ];
    }
    public static function getRelations(): array
    {
        return [
            'today' => Relation::hasMany(DayTransactionDetails::class),
            'other_day' => Relation::hasMany(DayTransactionDetails::class)
        ];
    }
    public static function getFields(): array
    {
        return [];
    }
}
