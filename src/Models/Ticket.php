<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Contracts\ModelEnum;
use Ako\Zarinpal\Php\Contracts\TicketFeedbackTypeEnum;
use Ako\Zarinpal\Php\Contracts\TicketPriorityEnum;
use Ako\Zarinpal\Php\Contracts\TicketReplyFeedbackTypeEnum;
use Ako\Zarinpal\Php\Contracts\TicketStatusEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\GraphQLQuery;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use Ako\Zarinpal\Php\Traits\HasQueries;

class Ticket extends BaseModel
{
    use IsSelectable, HasQueries;
    public static function getRelations(): array
    {
        return [
            'assigned_user' => Relation::hasOne(UserPublic::class),
            'second_user' => Relation::hasOne(UserPublic::class),
            'replies' => Relation::hasMany(TicketReply::class),
            'department' => Relation::hasOne(TicketDepartment::class),
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(),
            'ticket_department_id' => Type::ID(),
            'priority' => Type::enum(TicketPriorityEnum::class),
            'status' => Type::enum(TicketStatusEnum::class),
            'first_user_seen' => Type::bool(),
            'second_user_seen' => Type::bool(),
            'title' => Type::string(),
            'updated_at' => Type::datetime(),
            'created_at' => Type::datetime(),
            'model_type' => Type::string(),
            'model_id' => Type::ID(),
            'flag' => Type::string(),
            'feedback_type' => Type::enum(TicketFeedbackTypeEnum::class),
            'rate' => Type::integer()
        ];
    }

    public static function getSelectOperation(): string
    {
        return static::$select_operation = "Tickets";
    }
    public static function getSelectArguments(): array
    {
        return static::$select_arguments = [
            'id' => Type::ID(),
            'status' => Type::enum(TicketStatusEnum::class),
            'offset' => Type::integer(),
            'limit' => Type::integer()
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "TicketAdd",
                'as' => "insert",
                'arguments' => [
                    'ticket_department_id' => Type::ID(true),
                    'priority' => Type::enum(TicketPriorityEnum::class),
                    'title' => Type::string(true),
                    'content' => Type::string(true),
                    'attachment' => Type::string(),
                    'model_type' => Type::enum(ModelEnum::class),
                    'model_id' => Type::ID()
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "TicketClose",
                'as' => "close",
                'arguments' => [
                    'id' => Type::ID(true)
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "TicketFeedback",
                'as' => "feedback",
                'arguments' => [
                    'id' => Type::ID(true),
                    'feedback_type' => Type::enum(TicketFeedbackTypeEnum::class),
                    'rate' => Type::integer()
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "SupportResponseTicketsCount",
                'as' => "countSupportResponses",
                'arguments' => [],
                'return_type' => Type::integer()
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "UnreadTicketsCount",
                'as' => "countUnread",
                'arguments' => [],
                'return_type' => Type::integer()
            ]
        ];
    }
}
