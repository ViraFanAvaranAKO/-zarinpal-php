<?php

namespace Ako\Zarinpal\Php\Contracts;

use Ako\Zarinpal\Php\Helpers\Utils;

interface IZarinpalTransaction
{
    /**
     * Set's the transaction merchant id. 
     *
     * @param StrategiesEnum $value : Must be either merchant id string or Terminal instance
     *
     * @return $this
     */
    public function useTerminal($value);

    /**
     * Set's the transaction merchant id to the default from config. 
     *
     * @return $this
     */
    public function useDefaultTerminal();

    /**
     * If no argument passed, returns the driver strategy; Sets the driver strategy if strategy passed
     *
     * @param StrategiesEnum $strategy 
     *
     * @return $this|StrategiesEnum
     */
    public function strategy($strategy = Utils::UNIQUE_SYMBOL);

    /**
     * If no argument passed, returns the transaction currency; Sets the transaction currency if currency passed
     *
     * @param CurrenciesEnum $strategy 
     *
     * @return $this|CurrenciesEnum
     */
    public function currency($currency = Utils::UNIQUE_SYMBOL);

    /**
     * If no argument passed, returns the invoice amount; Sets the amount if number passed
     *
     * @param int $amount 
     *
     * @return $this|int|null
     */
    public function amount($amount = Utils::UNIQUE_SYMBOL);

    /**
     * If no argument passed, returns the transaction authority; Sets the transaction authority if string passed
     *
     * @param string $value 
     *
     * @return $this|string|null
     */
    public function authority($value = Utils::UNIQUE_SYMBOL);

    /**
     * If no argument passed, returns the transaction callback URL; Sets the transaction callback URL if string passed
     *
     * @param string $value 
     *
     * @return $this|string|null
     */
    public function callback($value = Utils::UNIQUE_SYMBOL);

    /**
     * If no argument passed, returns the user mobile; Sets the user mobile if string passed
     *
     * @param string $value
     *
     * @return $this|string|null
     */
    public function mobile($value = Utils::UNIQUE_SYMBOL);

    /**
     * If no argument passed, returns the user email; Sets the user email if string passed
     *
     * @param string $value
     *
     * @return $this|string|null
     */
    public function email($value = Utils::UNIQUE_SYMBOL);

    /**
     * If no argument passed, returns the transaction description; Sets the transaction description if string passed
     *
     * @param string $value
     *
     * @return $this|string|null
     */
    public function description($value = Utils::UNIQUE_SYMBOL);

    /**
     * If no argument passed as value, returns the given metadata key; Sets the given metadata key if value passed
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this|mixed|null
     */
    public function metadata(string $key, $value = Utils::UNIQUE_SYMBOL);

    /**
     * Gets the transaction gateway URL
     *
     * @return string|null
     */
    public function gateway();

    /**
     * Create new payment request
     *
     * @return string
     * 
     * @throws \Ako\Zarinpal\Php\Exceptions\TransactionFailedException
     */
    public function request(): string;

    /**
     * verify the payment
     *
     * @return \Ako\Zarinpal\Php\Helpers\Receipt
     * 
     * @throws \Ako\Zarinpal\Php\Exceptions\TransactionFailedException
     */
    public function verify(): \Ako\Zarinpal\Php\Helpers\Receipt;

    public function toArray(): array;
}
