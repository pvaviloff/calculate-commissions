<?php

declare(strict_types=1);

namespace Pvavilov\CalculateCommissions\Service;

use Pvavilov\CalculateCommissions\Exception\BinlistException;
use \GuzzleHttp\Client;

class BinlistClient
{
    const BINLIST_URI = 'https://lookup.binlist.net/';

    private Client $binlistClient;

    public function __construct() 
    {
        $this->binlistClient = new Client([
            'base_uri' => self::BINLIST_URI,
        ]);
    }

    public function getCountryAlpha2ByBin(int $bin): string
    {
        try {
            $response = $this->binlistClient->request('GET', (string) $bin);
        } catch (\Throwable $e) {
            throw new BinlistException($e->getMessage());
        }

        return json_decode($response->getBody()->getContents())->country->alpha2;
    }

}
