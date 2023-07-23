<?php

declare(strict_types=1);

namespace Pvavilov\CalculateCommissions\ValueObject;

use Pvavilov\CalculateCommissions\Exception\TransactionObjectException;

final class TransactionObject
{
    public readonly int $bin;

    public readonly float $amount;

    public readonly string $currency;

    public function __construct(
        int $bin,
        float $amount,
        string $currency,
    ) {
        if ($bin <= 0) {
            throw new TransactionObjectException('Bin format is incorrect');
        }
        if ($amount <= 0) {
            throw new TransactionObjectException('Amount must be bigger than 0');
        }
        if (empty($currency)) {
            throw new TransactionObjectException('Currency is empty');
        }

        $this->bin = $bin;
        $this->amount = $amount;
        $this->currency = $currency;
    }

}
