<?php

namespace Ako\Zarinpal\Php\Traits;

use Ako\Zarinpal\Php\Helpers\Type;

trait IsSelectable
{
    protected static ?string $select_operation = null;
    protected static ?array $select_arguments = null;

    /**
     * @return array<string, Type>
     */
    public abstract static function getSelectArguments(): array;
    public abstract static function getSelectOperation(): string;
}
