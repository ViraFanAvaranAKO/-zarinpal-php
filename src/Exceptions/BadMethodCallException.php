<?php

namespace Ako\Zarinpal\Php\Exceptions;

use InvalidArgumentException;

/**
 * Class InvalidSelectionException
 */
class BadMethodCallException extends InvalidArgumentException
{
    public function __construct($message = "")
    {
        parent::__construct($message);
    }
}
