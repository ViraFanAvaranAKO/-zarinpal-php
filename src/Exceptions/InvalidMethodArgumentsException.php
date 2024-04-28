<?php

namespace Ako\Zarinpal\Php\Exceptions;

use InvalidArgumentException;

/**
 * Class InvalidSelectionException
 */
class InvalidMethodArgumentsException extends InvalidArgumentException
{
    public function __construct($method = "", $all_keys = [], $required_keys = [])
    {
        if (count($all_keys)) {
            if(count($required_keys)){
                parent::__construct("The method ({$method}) accepts at least one array argument with at least these keys: " . implode(', ', $required_keys));
            }else{
                parent::__construct("The method ({$method}) accepts zero or one array argument with these possible keys: " . implode(', ', $all_keys));
            }
        } else {
            parent::__construct("The method ({$method}) accepts no arguments.");
        }
    }
}
