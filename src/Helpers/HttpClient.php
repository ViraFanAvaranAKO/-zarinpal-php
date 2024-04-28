<?php

namespace Ako\Zarinpal\Php\Helpers;

use Ako\Zarinpal\Php\Abstracts\Core;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Utils;
use TypeError;

/**
 * Class Mutation
 */
class HttpClient
{
    protected Client $httpClient;
    protected $httpHeaders;
    protected $options;
    protected $auth;

    public function __construct(Core $core)
    {
        $authorizationHeaders = ['Authorization' => "Bearer {$core->getSettings()->access_token}"];
        $httpOptions = [
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false
            ]
        ];
        $headers = array_merge(
            $authorizationHeaders,
            $httpOptions['headers'] ?? [],
            ['Content-Type' => 'application/json']
        );
        unset($httpOptions['headers']);
        $this->options = $httpOptions;

        $this->httpClient           = new Client($httpOptions);
        $this->httpHeaders          = $headers;
    }

    public function runQuery($query, bool $resultsAsArray = false, array $variables = []): Results
    {
        if (!$query instanceof GraphQLQuery) {
            throw new TypeError('Client::runQuery accepts the first argument of type GraphQLQuery');
        }

        return $this->runRawQuery((string) $query, $resultsAsArray, $variables);
    }

    public function runRawQuery(string $queryString, $resultsAsArray = false, array $variables = []): Results
    {
        $request = new Request('POST', "https://next.zarinpal.com/api/v4/graphql");

        foreach ($this->httpHeaders as $header => $value) {
            $request = $request->withHeader($header, $value);
        }

        // Convert empty variables array to empty json object
        if (empty($variables)) $variables = (object) null;
        // Set query in the request body
        $bodyArray = ['query' => (string) $queryString, 'variables' => $variables];
        $request = $request->withBody(Utils::streamFor(json_encode($bodyArray)));

        if ($this->auth) {
            $request = $this->auth->run($request, $this->options);
        }

        // Send api request and get response
        try {
            $response = $this->httpClient->send($request);
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
            if ($response->getStatusCode() !== 400) {
                throw $exception;
            }
        }

        return new Results($response, $resultsAsArray);
    }
}
