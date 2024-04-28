<?php

namespace Ako\Zarinpal\Php\Contracts;

use Ako\Zarinpal\Php\Helpers\Utils;

interface IZarinpalQueryable
{
    public function __call($method, $args);
}
