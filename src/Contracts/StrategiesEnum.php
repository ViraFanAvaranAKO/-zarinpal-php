<?php

namespace Ako\Zarinpal\Php\Contracts;

use Ako\Zarinpal\Php\Abstracts\Core;
use Ako\Zarinpal\Php\Abstracts\Strategy;
use Ako\Zarinpal\Php\Drivers\TransactionDriver;
use Ako\Zarinpal\Php\Helpers\Utils;
use Ako\Zarinpal\Php\Helpers\Invoice;
use Ako\Zarinpal\Php\Helpers\Normal;
use Ako\Zarinpal\Php\Helpers\Sandbox;
use Ako\Zarinpal\Php\Helpers\Zaringate;

enum StrategiesEnum: string
{
    case Sandbox = "sandbox";
    case Normal = "normal";
    case Zaringate = "zaringate";

    public function instantiate_driver(Core $core, array $data = []): IZarinpalTransaction
    {
        $data = Utils::array_merge_by_reference([
            'amount' => null,
            'authority' => null,
            'callback' => null,
            'mobile' => null,
            'email' => null,
            'description' => null,
            'metadata' => [],
            "currency" => "IRT",
        ], $data);
        $data['strategy'] = $this->value;
        return new TransactionDriver($core, $data);
    }
    public function instantiate_strategy(Core $core, Invoice $invoice): Strategy
    {
        return match ($this) {
            self::Sandbox => new Sandbox($core, $invoice),
            self::Normal => new Normal($core, $invoice),
            self::Zaringate => new Zaringate($core, $invoice),
        };
    }
}
