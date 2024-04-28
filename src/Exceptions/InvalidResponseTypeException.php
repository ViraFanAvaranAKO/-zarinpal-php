<?php

namespace Ako\Zarinpal\Php\Exceptions;

use InvalidArgumentException;

/**
 * Class InvalidSelectionException
 */
class InvalidResponseTypeException extends InvalidArgumentException
{
    public function __construct($type, $value)
    {
        parent::__construct("The server responded with an unexpected data type . Expected: " . $type->expect() . "; Provided: " . var_export($value, true));
    }
}
