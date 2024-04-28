<?php

namespace Ako\Zarinpal\Php\Exceptions;

use InvalidArgumentException;

/**
 * Class InvalidSelectionException
 */
class InvalidArgumentValueException extends InvalidArgumentException
{
    public function __construct($name, $type, $value)
    {
        parent::__construct("Provided value for argument ({$name}) is not valid. Expected: " . $type->expect() . "; Provided: " . var_export($value, true));
    }
}
