<?php

namespace Ako\Zarinpal\Php\Helpers;

use Ako\Zarinpal\Php\Abstracts\Core;
use Ako\Zarinpal\Php\Abstracts\Strategy;
use Ako\Zarinpal\Php\Exceptions\TransactionFailedException;
use GuzzleHttp\Client;

class Normal extends Strategy
{
    protected Client $client;

    public function __construct(Core $core, Invoice $invoice)
    {
        parent::__construct($core, $invoice);
        $this->client = new Client();
    }

    public function getRequestUrl(): string
    {
        return "https://api.zarinpal.com/pg/v4/payment/request.json";
    }
    public function getGatewayUrl(): string|null
    {
        return $this->invoice->authority ? "https://www.zarinpal.com/pg/StartPay/" . $this->invoice->authority : null;
    }
    public function getVerificationUrl(): string
    {
        return "https://api.zarinpal.com/pg/v4/payment/verify.json";
    }
    public function request(): string
    {
        if ($this->invoice->authority) {
            return $this->getGatewayUrl();
        }

        if (!$this->invoice->isValidForRequest()) {
            throw new TransactionFailedException("Zarinpal payment request failed due to incomplete/invalid transaction data. (exit code -422)", -422);
        }


        $data = [
            "merchant_id" => $this->invoice->merchant,
            "amount" => $this->invoice->amount,
            "currency" => $this->invoice->currency,
            "callback_url" => $this->invoice->callback,
            "description" => $this->invoice->description,
        ];
        if ($this->invoice->mobile || $this->invoice->email || isset($this->invoice->metadata['order_id'])) {
            $data['metadata'] = [];

            if ($this->invoice->mobile) {
                $data['metadata']['mobile'] = $this->invoice->mobile;
            }
            if ($this->invoice->email) {
                $data['metadata']['email'] = $this->invoice->email;
            }
            if (isset($this->invoice->metadata['order_id'])) {
                $data['metadata']['order_id'] = $this->invoice->metadata['order_id'];
            }
        }

        $response = $this
            ->client
            ->request(
                'POST',
                $this->getRequestUrl(),
                [
                    "json" => $data,
                    "headers" => [
                        'Content-Type' => 'application/json',
                    ],
                    "http_errors" => false,
                ]
            );

        $result = json_decode($response->getBody()->getContents(), true);

        if (!empty($result['errors']) || empty($result['data']) || $result['data']['code'] != 100) {
            $bodyResponse = $result['errors']['code'];
            throw new TransactionFailedException("Zarinpal payment request failed. (exit code " . $bodyResponse . ")", $bodyResponse);
        }

        $this->invoice->authority = ($result['data']["authority"]);

        return $this->getGatewayUrl();
    }
    public function verify(): Receipt
    {
        if (!$this->invoice->isValidForValidation()) {
            throw new TransactionFailedException("Zarinpal validation request failed due to incomplete/invalid transaction data. (exit code -422)", -422);
        }

        $data = [
            "merchant_id" => $this->invoice->merchant,
            "authority" => $this->invoice->authority,
            "amount" => $this->invoice->amount,
        ];

        $response = $this->client->request(
            'POST',
            $this->getVerificationUrl(),
            [
                'json' => $data,
                "headers" => [
                    'Content-Type' => 'application/json',
                ],
                "http_errors" => false,
            ]
        );

        $result = json_decode($response->getBody()->getContents(), true);

        if (empty($result['data']) || !isset($result['data']['ref_id']) || ($result['data']['code'] != 100 && $result['data']['code'] != 101)) {
            $bodyResponse = $result['errors']['code'];
            throw new TransactionFailedException("Zarinpal validation request failed. (exit code " . $bodyResponse . ")", $bodyResponse);
        }

        return new Receipt([
            'code' => $result['data']['code'],
            'message' => $result['data']['message'] ?? null,
            'card_hash' => $result['data']['card_hash'] ?? null,
            'card_pan' => $result['data']['card_pan'] ?? null,
            'ref_id' => $result['data']['ref_id'],
            'fee_type' => $result['data']['fee_type'] ?? null,
            'fee' => $result['data']['fee'] ?? null,
            'order_id' => $result['data']['order_id'] ?? null,
        ]);
    }
}
