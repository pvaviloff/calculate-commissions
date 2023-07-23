<?php

namespace Pvavilov\CalculateCommissions\Tests\unit\ValueObject;

use PHPUnit\Framework\TestCase;
use Pvavilov\CalculateCommissions\Exception\TransactionObjectException;
use Pvavilov\CalculateCommissions\ValueObject\TransactionObject;

final class TransactionObjectTest extends TestCase
{
    public function testEmptyBinException(): void
    {
        $this->expectException(TransactionObjectException::class);
        $this->expectExceptionMessage('Bin format is incorrect');

        new TransactionObject((int) '', 1.00, 'EUR');
    }

    public function testNegativeBinException(): void
    {
        $this->expectException(TransactionObjectException::class);
        $this->expectExceptionMessage('Bin format is incorrect');

        new TransactionObject(-12345, 1.00, 'EUR');
    }

    public function testZeroBinException(): void
    {
        $this->expectException(TransactionObjectException::class);
        $this->expectExceptionMessage('Bin format is incorrect');

        new TransactionObject(0, 1.00, 'EUR');
    }

    public function testNegativeAmountException(): void
    {
        $this->expectException(TransactionObjectException::class);
        $this->expectExceptionMessage('Amount must be bigger than 0');

        new TransactionObject(12345, -1.00, 'EUR');
    }

    public function testZeroAmountException(): void
    {
        $this->expectException(TransactionObjectException::class);
        $this->expectExceptionMessage('Amount must be bigger than 0');

        new TransactionObject(12345, 0, 'EUR');
    }

    public function testEmptyCurrencyException(): void
    {
        $this->expectException(TransactionObjectException::class);
        $this->expectExceptionMessage('Currency is empty');

        new TransactionObject(12345, 12345, '');
    }

}
