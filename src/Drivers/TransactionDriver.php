<?php

namespace Ako\Zarinpal\Php\Drivers;

use Ako\Zarinpal\Php\Abstracts\Core;
use Ako\Zarinpal\Php\Abstracts\Strategy;
use Ako\Zarinpal\Php\Contracts\IZarinpalTransaction;
use Ako\Zarinpal\Php\Contracts\StrategiesEnum;
use Ako\Zarinpal\Php\Helpers\Receipt;
use Ako\Zarinpal\Php\Helpers\Utils;
use Ako\Zarinpal\Php\Helpers\Invoice;

class TransactionDriver implements IZarinpalTransaction
{
    protected Core $_core;
    protected Invoice $_invoice;

    public function __construct(Core $core, array $data = [])
    {
        $this->_core = $core;
        $this->_invoice = new Invoice($core, $data);
        if (!isset($data['currency'])) {
            $this->currency($core->getSettings()->defaults->currency);
        }
    }

    public function useTerminal($value)
    {
        if (false) { // Check if is an instance of Terminal model
            $this->_invoice->merchant = $value->key;
        } elseif (is_string($value) && $value != "") {
            $this->_invoice->merchant = $value;
        }
        return $this;
    }
    public function useDefaultTerminal()
    {
        $this->_invoice->merchant = $this->_core->getSettings()->defaults->merchant_id;
        return $this;
    }

    public function strategy($strategy = Utils::UNIQUE_SYMBOL)
    {
        if ($strategy === Utils::UNIQUE_SYMBOL) {
            return $this->_invoice->strategy;
        } else {
            $this->_invoice->strategy = $strategy;
            return $this;
        }
    }

    public function currency($currency = Utils::UNIQUE_SYMBOL)
    {
        if ($currency === Utils::UNIQUE_SYMBOL) {
            return $this->_invoice->currency;
        } else {
            $this->_invoice->currency = $currency;
            return $this;
        }
    }

    public function amount($amount = Utils::UNIQUE_SYMBOL)
    {
        if ($amount === Utils::UNIQUE_SYMBOL) {
            return $this->_invoice->amount;
        } else {
            $this->_invoice->amount = $amount;
            return $this;
        }
    }

    public function authority($value = Utils::UNIQUE_SYMBOL)
    {
        if ($value === Utils::UNIQUE_SYMBOL) {
            return $this->_invoice->authority;
        } else {
            $this->_invoice->authority = $value;
            return $this;
        }
    }

    public function callback($value = Utils::UNIQUE_SYMBOL)
    {
        if ($value === Utils::UNIQUE_SYMBOL) {
            return $this->_invoice->callback;
        } else {
            $this->_invoice->callback = $value;
            return $this;
        }
    }
    public function mobile($value = Utils::UNIQUE_SYMBOL)
    {
        if ($value === Utils::UNIQUE_SYMBOL) {
            return $this->_invoice->mobile;
        } else {
            $this->_invoice->mobile = $value;
            return $this;
        }
    }
    public function email($value = Utils::UNIQUE_SYMBOL)
    {
        if ($value === Utils::UNIQUE_SYMBOL) {
            return $this->_invoice->email;
        } else {
            $this->_invoice->email = $value;
            return $this;
        }
    }
    public function description($value = Utils::UNIQUE_SYMBOL)
    {
        if ($value === Utils::UNIQUE_SYMBOL) {
            return $this->_invoice->description;
        } else {
            $this->_invoice->description = $value;
            return $this;
        }
    }
    public function metadata(string $key, $value = Utils::UNIQUE_SYMBOL)
    {
        if ($value === Utils::UNIQUE_SYMBOL) {
            $result = $this->_invoice->metadata;
            return isset($result[$key]) ? $result[$key] : null;
        } else {
            $this->_invoice->setMetadata($key, $value);
            return $this;
        }
    }
    public function gateway()
    {
        return $this->_invoice->instance->getGatewayUrl();
    }

    public function request(): string
    {
        return $this->_invoice->instance->request();
    }
    public function verify(): Receipt
    {
        return $this->_invoice->instance->verify();
    }

    public function toArray(): array
    {
        return [
            ...$this->_invoice->toArray(),
            'gateway' => $this->gateway(),
        ];
    }
}
