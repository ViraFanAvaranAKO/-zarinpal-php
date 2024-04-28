<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Contracts\TicketReplyFeedbackTypeEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class TicketReply extends BaseModel
{
    use HasQueries;
    public static function getRelations(): array
    {
        return [
            'user' => Relation::hasOne(UserPublic::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(),
            'ticket_id' => Type::ID(),
            'content' => Type::string(),
            'attachment' => Type::string(),
            'feedback_type' => Type::enum(TicketReplyFeedbackTypeEnum::class),
            'updated_at' => Type::datetime(),
            'created_at' => Type::datetime()
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "TicketAddReply",
                'as' => "insert",
                'arguments' => [
                    'ticket_id' => Type::ID(true),
                    'content' => Type::string(true),
                    'attachment' => Type::string()
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "TicketReplyFeedback",
                'as' => "feedback",
                'arguments' => [
                    'id' => Type::ID(true),
                    'feedback_type' => Type::enum(TicketReplyFeedbackTypeEnum::class)
                ],
                'return_type' => Type::bool()
            ]
        ];
    }
}
