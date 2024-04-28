<?php

namespace Ako\Zarinpal\Php\Abstracts;

use Ako\Zarinpal\Php\Helpers\Invoice;
use Ako\Zarinpal\Php\Helpers\Receipt;

abstract class Strategy
{
    protected Core $_core;
    protected Invoice $invoice;

    public function __construct(Core $core, Invoice $invoice)
    {
        $this->_core = $core;
        $this->invoice = $invoice;
    }

    abstract public function getRequestUrl(): string;
    abstract public function getGatewayUrl(): string|null;
    abstract public function getVerificationUrl(): string;
    abstract public function request(): string;
    abstract public function verify(): Receipt;
}
