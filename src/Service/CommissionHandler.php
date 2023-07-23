<?php

declare(strict_types=1);

namespace Pvavilov\CalculateCommissions\Service;

use Pvavilov\CalculateCommissions\ValueObject\TransactionObject;

final class CommissionHandler
{
    const EU_COUNTRIES = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    const DEFAULT_COMMISSION_RATE = 0.02;

    const EU_COMMISSION_RATE = 0.01;

    public function __construct(
        private BinlistClient $binlistClient,
        private ExchangeRateClient $exchangeRateClient,
    ) {}

    private function getCommissionRateByCountryAlpha2(string $countryAlpha2): float
    {
        return in_array($countryAlpha2, self::EU_COUNTRIES) ? self::EU_COMMISSION_RATE : self::DEFAULT_COMMISSION_RATE;
    }

    public function calculate(TransactionObject $transactionObject): float
    {
        $rate = $this->exchangeRateClient->getRateByCurrency($transactionObject->currency);
        $countryAlpha2 = $this->binlistClient->getCountryAlpha2ByBin($transactionObject->bin);
        $commissionRate = $this->getCommissionRateByCountryAlpha2($countryAlpha2);

        $commission = $transactionObject->amount / $rate * $commissionRate;

        return ceil($commission * 100) / 100;
    }

}
