<?php

namespace Ako\Zarinpal\Php\Exceptions;

use InvalidArgumentException;

/**
 * Class InvalidSelectionException
 */
class InvalidSelectionException extends InvalidArgumentException
{
    public function __construct($message = "")
    {
        parent::__construct($message);
    }
}
