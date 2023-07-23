<?php

declare(strict_types=1);

namespace Pvavilov\CalculateCommissions\Service;

use Pvavilov\CalculateCommissions\Exception\ExchangeRateException;
use \GuzzleHttp\Client;

class ExchangeRateClient
{
    const EXCHANGE_RATE_URI = 'http://api.exchangeratesapi.io/v1/';

    const LATEST_ROUTE = 'latest';

    private array $rates;

    public function __construct(string $apiAccessKey) 
    {
        if (empty($apiAccessKey)) {
            throw new ExchangeRateException("API access key is missing");
        }

        $exchangeRateClient = new Client([
            'base_uri' => self::EXCHANGE_RATE_URI,
        ]);
        try {
            $response = $exchangeRateClient->request('GET', self::LATEST_ROUTE, [
                'query' => [
                    'access_key' => $apiAccessKey,
    
                ]
            ]);
        } catch (\Throwable $e) {
            throw new ExchangeRateException($e->getMessage());
        }
        
        $this->rates = json_decode($response->getBody()->getContents(), true)['rates'];
    }

    public function getRateByCurrency(string $currency): float
    {
        if (!array_key_exists($currency, $this->rates)) {
            throw new ExchangeRateException("Unknown currency: {$currency}");
        }

        return $this->rates[$currency];
    }

}
