<?php

namespace Ako\Zarinpal\Php\Models;

use Ako\Zarinpal\Php\Abstracts\BaseModel;
use Ako\Zarinpal\Php\Contracts\UserVipLevelEnum;
use Ako\Zarinpal\Php\Contracts\BankAccountTypeEnum;
use Ako\Zarinpal\Php\Contracts\GenderEnum;
use Ako\Zarinpal\Php\Contracts\InvoiceStatusEnum;
use Ako\Zarinpal\Php\Contracts\TicketReplyFeedbackTypeEnum;
use Ako\Zarinpal\Php\Contracts\UserLevelEnum;
use Ako\Zarinpal\Php\Helpers\GraphQLMutation;
use Ako\Zarinpal\Php\Helpers\GraphQLQuery;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Traits\HasQueries;

class User extends BaseModel
{
    use HasQueries;
    public static function getRelations(): array
    {
        return [
            'notification_preferences' => Relation::hasMany(UserNotificationPreferences::class),
            'verifications' => Relation::hasMany(VerificationOutput::class),
            'social_info' => Relation::hasMany(SocialInfo::class),
            'addresses' => Relation::hasMany(Address::class),
            'verification_step' => Relation::hasOne(VerificationStep::class),
            'personal_link' => Relation::hasOne(Terminal::class),
            'terminals' => Relation::hasMany(Terminal::class),
            'signature' => Relation::hasOne(Signature::class),
            'referrers' => Relation::hasMany(UserPublic::class),
            'additional_identification_info' => Relation::hasOne(AdditionalIdentificationInfo::class),
            'support' => Relation::hasOne(User::class)
        ];
    }
    public static function getFields(): array
    {
        return [
            'id' => Type::ID(true),
            'referrer_user_id' => Type::ID(),
            'company_rid' => Type::ID(),
            'company_name' => Type::string(),
            'company_register_id' => Type::string(),
            'company_registered_at' => Type::date(),
            'email' => Type::string(),
            'avatar' => Type::string(),
            'first_name' => Type::string(),
            'last_name' => Type::string(),
            'full_name' => Type::string(),
            'username' => Type::string(),
            'cell_number' => Type::string(),
            'ssn' => Type::string(),
            'gender' => Type::enum(GenderEnum::class),
            'mail_subscription' => Type::bool(),
            'level' => Type::enum(UserLevelEnum::class),
            'roles' => Type::list(Type::string()),
            'permissions' => Type::list(Type::string()),
            'birthday' => Type::date(),
            'is_suspend' => Type::bool(),
            'new_panel' => Type::bool(),
            'is_enamad_flow' => Type::bool(),
            'ws_id' => Type::string(),
            'tax_id' => Type::string(),
            'referral_id' => Type::string(),
            'encrypted_id' => Type::string(),
            'vip_level' => Type::enum(UserVipLevelEnum::class),
            'can_handle_edit_permissions' => Type::bool(),
            'created_at' => Type::datetime()
        ];
    }

    public static function getExtraQueries(): array
    {
        return static::$queryables ??= [
            [
                'type' => GraphQLMutation::class,
                'operation' => "ActiveTelegram",
                'as' => "activateTelegram",
                'arguments' => [
                    'uuid' => Type::string()
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "DeactivateTelegram",
                'as' => "deactivateTelegram",
                'arguments' => [],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "PreferencesEdit",
                'as' => "updatePreferences",
                'arguments' => [
                    'notification_preferences' => Type::list(Type::model(InputUserNotificationPreferences::class)),
                    'email' => Type::string(),
                    'mail_subscription' => Type::bool(),
                    'gender' => Type::enum(GenderEnum::class),
                    'first_name' => Type::string(),
                    'last_name' => Type::string(),
                    'company_rid' => Type::string(),
                    'company_name' => Type::string(),
                    'company_registered_at' => Type::date(),
                    'birthday' => Type::date(),
                    'ssn' => Type::string(),
                    'tax_id' => Type::string()
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "SendVerifyEmail",
                'as' => "requestEmailVerification",
                'arguments' => [],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "VerifyEmail",
                'as' => "validateEmailVerification",
                'arguments' => [
                    'otp_code' => Type::string(true)
                ],
                'return_type' => Type::bool()
            ],
            [
                'type' => GraphQLMutation::class,
                'operation' => "UpdateDocuments",
                'as' => "updateDocuments",
                'arguments' => [
                    'national_card_documents' => Type::ID(),
                    'selfie_documents' => Type::ID(),
                    'legal_documents' => Type::ID(),
                    'has_legal_documents' => Type::bool()
                ],
                'return_type' => Type::model(self::class)
            ],
            [
                'type' => GraphQLQuery::class,
                'operation' => "Me",
                'as' => "getMe",
                'arguments' => [],
                'return_type' => Type::model(self::class)
            ]
        ];
    }
}
