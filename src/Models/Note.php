<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\BankAccountStatusEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Contracts\NoteModelTypeEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class Note extends BaseModel
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
            'id' => Type::string(true),
            'content' => Type::string(),
            'created_at' => Type::datetime(),
            'updated_at' => Type::datetime(),
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "NoteAdd",
                'as' => "insert",
                'arguments' => [
                    'model_type' => Type::enum(NoteModelTypeEnum::class),
                    'model_id' => Type::ID(true),
                    'content' => Type::string(true)
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "NoteEdit",
                'as' => "update",
                'arguments' => [
                    'id' => Type::ID(true),
                    'content' => Type::string(true)
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "NoteDelete",
                'as' => "delete",
                'arguments' => [
                    'id' => Type::ID(true)
                ],
                'return_type' => Type::bool()
            ]
        ];
    }
}
