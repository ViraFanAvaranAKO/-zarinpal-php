<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class AdditionalIdentificationInfo extends BaseModel
{
    public static function getEagerLoads(): array
    {
        return [
            'passport', 'ssh_info', 'biz_info'
        ];
    }
    public static function getRelations(): array
    {
        return [
            'passport' => Relation::hasOne(Passport::class),
            'ssh_info' => Relation::hasOne(SshInfo::class),
            'biz_info' => Relation::hasOne(BizInfo::class),
        ];
    }
    public static function getFields(): array
    {
        return [];
    }
}
