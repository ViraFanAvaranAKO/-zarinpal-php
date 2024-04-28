<?php

namespace Ako\Zarinpal\Php\Helpers;

use Ako\Zarinpal\Php\Abstracts\Core;
use Ako\Zarinpal\Php\Abstracts\Strategy;
use Ako\Zarinpal\Php\Api;
use Ako\Zarinpal\Php\Contracts\CurrenciesEnum;
use Ako\Zarinpal\Php\Contracts\StrategiesEnum;
use Ako\Zarinpal\Php\Helpers\Utils;

class Invoice
{
    /**
     * @var object
     */
    private $data;
    protected Core $_core;
    protected Strategy $_instance;
    protected string $_merchant_id;

    public function __construct(Core $core, ?array $data = [])
    {
        $this->_core = $core;
        $this->data = (object) Utils::array_merge_by_reference([
            'amount' => null,
            'authority' => null,
            'callback' => null,
            'mobile' => null,
            'email' => null,
            'description' => null,
            'metadata' => [],
            "currency" => $this->_core->getSettings()->defaults->currency,
            "strategy" => $this->_core->getSettings()->defaults->strategy
        ], $data);
        $this->resetInvalidData();
        $this->_instance = $this->data->strategy->instantiate_strategy($this->_core, $this);
        $this->_merchant_id = isset($data['merchant_id']) && is_string($data['merchant_id']) && $data['merchant_id'] != "" ? $data['merchant_id'] : $this->_core->getSettings()->defaults->merchant_id;
    }

    private function resetInvalidData()
    {
        if (!is_null($this->data->amount) && !is_int($this->data->amount) || $this->data->amount < 1000) {
            $this->data->amount = null;
        }
        if (!is_null($this->data->authority) && !is_string($this->data->authority)) {
            $this->data->authority = null;
        }
        if (!is_null($this->data->callback) && !is_string($this->data->callback)) {
            $this->data->callback = null;
        }
        if (!is_null($this->data->mobile) && !is_string($this->data->mobile)) {
            $this->data->mobile = null;
        }
        if (!is_null($this->data->email) && !is_string($this->data->email)) {
            $this->data->email = null;
        }
        if (!is_null($this->data->description) && !is_string($this->data->description)) {
            $this->data->description = null;
        }
        if (!is_array($this->data->metadata)) {
            $this->data->metadata = [];
        }
        if (Utils::as_enum_or_null(CurrenciesEnum::class, $this->data->currency) == null) {
            $this->data->currency = $this->_core->getSettings()->defaults->currency;
        } else {
            $this->data->currency = Utils::as_enum_or_null(CurrenciesEnum::class, $this->data->currency);
        }
        if (Utils::as_enum_or_null(StrategiesEnum::class, $this->data->strategy) == null) {
            $this->data->strategy = $this->_core->getSettings()->defaults->strategy;
        } else {
            $this->data->strategy = Utils::as_enum_or_null(StrategiesEnum::class, $this->data->strategy);
        }
    }

    public function setMetadata(string $key, $value = null)
    {
        return $this->data->metadata[$key] = $value;
    }

    function __get($prop)
    {
        $prop = strtolower($prop);
        if ($prop == "instance") {
            return $this->_instance;
        }
        if ($prop == "merchant") {
            return $this->_merchant_id;
        }
        if ($prop == "metadata") {
            // Filter null values
            return  array_filter($this->data->metadata, function ($value) {
                return !is_null($value);
            });
        }
        return  $this->data->$prop;
    }

    function __set($prop, $val)
    {
        $prop = strtolower($prop);
        switch ($prop) {
            case 'merchant':
                if (is_string($val) && $val != "") {
                    $this->_merchant_id = $val;
                }
                break;
            case 'strategy':
                if (Utils::as_enum_or_null(StrategiesEnum::class, $val) != null) {
                    $this->data->strategy = Utils::as_enum_or_null(StrategiesEnum::class, $val);
                    $this->_instance = $this->data->strategy->instantiate_strategy($this->_core, $this);
                }
                break;
            case 'amount':
                if (is_null($val) || (is_int($val) && $val >= 1000)) {
                    $this->data->amount = $val;
                }
                break;
            case 'authority':
                if (is_null($val) || is_string($val)) {
                    $this->data->authority = $val;
                }
                break;
            case 'callback':
                if (is_null($val) || is_string($val)) {
                    $this->data->callback = $val;
                }
                break;
            case 'mobile':
                if (is_null($val) || is_string($val)) {
                    $this->data->mobile = $val;
                }
                break;
            case 'email':
                if (is_null($val) || is_string($val)) {
                    $this->data->email = $val;
                }
                break;
            case 'description':
                if (is_null($val) || is_string($val)) {
                    $this->data->description = $val;
                }
                break;
            case 'currency':
                if (Utils::as_enum_or_null(CurrenciesEnum::class, $val) != null) {
                    $this->data->currency = Utils::as_enum_or_null(CurrenciesEnum::class, $val);
                }
                break;
        }
    }

    public function isValidForRequest(): bool
    {
        $this->resetInvalidData();
        return !is_null($this->currency)
            && !is_null($this->amount)
            && is_int($this->amount)
            && $this->amount >= ($this->currency == CurrenciesEnum::Rial ? 10000 : 1000)
            && !is_null($this->callback)
            && is_string($this->callback)
            && str_starts_with($this->callback, "http")
            && !is_null($this->description)
            && is_string($this->description)
            && $this->description !== "";
    }
    public function isValidForValidation(): bool
    {
        $this->resetInvalidData();
        return !is_null($this->currency)
            && !is_null($this->amount)
            && is_int($this->amount)
            && $this->amount >= ($this->currency == CurrenciesEnum::Rial ? 10000 : 1000)
            && !is_null($this->authority)
            && is_string($this->authority)
            && $this->authority !== "";
    }

    public function toArray()
    {
        return [
            ...(array) $this->data,
            'merchant_id' => $this->_merchant_id
        ];
    }
}
