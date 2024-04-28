<?php

namespace Ako\Zarinpal\Php\Helpers;

use Ako\Zarinpal\Php\Abstracts\Strategy;
use Ako\Zarinpal\Php\Contracts\CurrenciesEnum;
use Ako\Zarinpal\Php\Exceptions\TransactionFailedException;

class Zaringate extends Strategy
{
    public function getRequestUrl(): string
    {
        return "https://ir.zarinpal.com/pg/services/WebGate/wsdl";
    }
    public function getGatewayUrl(): string|null
    {
        return $this->invoice->authority ? "https://www.zarinpal.com/pg/StartPay/" . $this->invoice->authority . "/ZarinGate" : null;
    }
    public function getVerificationUrl(): string
    {
        return "https://ir.zarinpal.com/pg/services/WebGate/wsdl";
    }
    public function request(): string
    {
        if ($this->invoice->authority) {
            return $this->getGatewayUrl();
        }

        if (!$this->invoice->isValidForRequest()) {
            throw new TransactionFailedException("Zaringate payment request failed due to incomplete/invalid transaction data. (exit code -422)", -422);
        }

        $data = [
            "MerchantID" => $this->invoice->merchant,
            "Amount" => $this->invoice->amount / ($this->invoice->currency == CurrenciesEnum::Toman ? 1 : 10),
            "CallbackURL" => $this->invoice->callback,
            "Description" => $this->invoice->description,
            "AdditionalData" => $this->invoice->metadata,
        ];
        if ($this->invoice->mobile) {
            $data['Mobile'] = $this->invoice->mobile;
        }
        if ($this->invoice->email) {
            $data['Email'] = $this->invoice->email;
        }

        $client = new \SoapClient($this->getRequestUrl(), ['encoding' => 'UTF-8']);
        $result = $client->PaymentRequest($data);

        $bodyResponse = $result->Status;
        if ($bodyResponse != 100 || empty($result->Authority)) {
            throw new TransactionFailedException("Zaringate payment request failed. (exit code " . $bodyResponse . ")", $bodyResponse);
        }

        $this->invoice->authority = $result->Authority;
        return $this->getGatewayUrl();
    }
    public function verify(): Receipt
    {
        if (!$this->invoice->isValidForValidation()) {
            throw new TransactionFailedException("Zaringate validation request failed due to incomplete/invalid transaction data. (exit code -422)", -422);
        }

        $data = [
            "MerchantID" => $this->invoice->merchant,
            'Authority' => $this->invoice->authority,
            'Amount' => $this->invoice->amount / ($this->invoice->currency == CurrenciesEnum::Toman ? 1 : 10),
        ];

        $client = new \SoapClient($this->getVerificationUrl(), ['encoding' => 'UTF-8']);
        $result = $client->PaymentVerification($data);

        $bodyResponse = $result->Status;
        if ($bodyResponse != 100) {
            throw new TransactionFailedException("Zaringate validation request failed. (exit code " . $bodyResponse . ")", $bodyResponse);
        }

        return new Receipt([
            'ref_id' => $result->RefID,
        ]);
    }
}
