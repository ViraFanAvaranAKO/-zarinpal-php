<?php

namespace Ako\Zarinpal\Php\Traits;

use Ako\Zarinpal\Php\Helpers\Type;

trait HasQueries
{
    protected static ?array $queryables = null;

    /**
     * @return array{type: "query"|"mutation", operation: string, as:string, arguments: array<string, Type>, return_type: Type}[] $queryables
     */
    public abstract static function getExtraQueries(): array;
}
