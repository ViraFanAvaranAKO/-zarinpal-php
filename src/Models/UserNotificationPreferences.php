<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\NotificationPreferenceChannelEnum;
use Ako\Zarinpal\Php\Contracts\NotificationPreferenceTypeEnum;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class UserNotificationPreferences extends BaseModel
{
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'type' => Type::enum(NotificationPreferenceTypeEnum::class, true),
            'channels' => Type::list(Type::enum(NotificationPreferenceChannelEnum::class), true)
        ];
    }
}
