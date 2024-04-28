<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\UserAnnouncementTypeEnum;
use Ako\Zarinpal\Php\Contracts\TicketReplyFeedbackTypeEnum;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class UserAnnouncement extends BaseModel
{
    use IsSelectable;
    public static function getRelations(): array
    {
        return [];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'title' => Type::string(true),
            'message' => Type::string(true),
            'url_title' => Type::string(),
            'url' => Type::string(),
            'type' => Type::list(Type::enum(UserAnnouncementTypeEnum::class)),
            'created_at' => Type::date()
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "UserAnnouncement";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [];
    }
}
