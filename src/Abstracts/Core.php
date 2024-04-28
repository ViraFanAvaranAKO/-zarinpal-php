<?php

namespace Ako\Zarinpal\Php\Abstracts;

use Ako\Zarinpal\Php\Contracts\CurrenciesEnum;
use Ako\Zarinpal\Php\Contracts\IZarinpal;
use Ako\Zarinpal\Php\Contracts\StrategiesEnum;
use Ako\Zarinpal\Php\Helpers\Utils;
use InvalidArgumentException;

abstract class Core implements IZarinpal
{
    /**
     * Settings
     *
     * @var object
     */
    protected $settings;

    public function __construct($settings)
    {
        $settings = Utils::array_merge_by_reference([
            'defaults' => [],
            'access_token' => null
        ], $settings);
        $settings['defaults'] = Utils::array_merge_by_reference([
            'merchant_id' => null,
            'currency' => null,
            'strategy' => null
        ], $settings['defaults']);
        $this->settings = Utils::array_to_object_deep($settings);
        $this->validate_settings();
    }

    protected function validate_settings()
    {
        if (!isset($this->settings) || $this->settings == null || !is_object($this->settings)) {
            throw new \InvalidArgumentException("Provided settings is not valid");
        }

        if (!isset($this->settings->defaults) || $this->settings->defaults == null || !is_object($this->settings->defaults)) {
            throw new \InvalidArgumentException("Provided settings does not provide valid defaults");
        }

        if (!isset($this->settings->defaults->merchant_id) || $this->settings->defaults->merchant_id == null || !is_string($this->settings->defaults->merchant_id) || $this->settings->defaults->merchant_id == "") {
            throw new \InvalidArgumentException("Provided settings does not provide a valid default merchant id");
        }

        if (!isset($this->settings->defaults->strategy) || Utils::as_enum_or_null(StrategiesEnum::class, $this->settings->defaults->strategy) == null) {
            throw new \InvalidArgumentException("Provided settings does not provide a valid default strategy");
        } else {
            $this->settings->defaults->strategy = Utils::as_enum_or_null(StrategiesEnum::class, $this->settings->defaults->strategy);
        }

        if (!isset($this->settings->defaults->currency) || Utils::as_enum_or_null(CurrenciesEnum::class, $this->settings->defaults->currency) == null) {
            throw new \InvalidArgumentException("Provided settings does not provide a valid default currency");
        } else {
            $this->settings->defaults->currency = Utils::as_enum_or_null(CurrenciesEnum::class, $this->settings->defaults->currency);
        }

        if ($this->settings->access_token !== null && (!is_string($this->settings->access_token) || $this->settings->access_token == "")) {
            throw new \InvalidArgumentException("Provided settings provides an invalid access token");
        }
    }

    public function getSettings(): object
    {
        return $this->settings;
    }
}
