<?php

declare(strict_types=1);

namespace Pvavilov\CalculateCommissions\Tests\unit\Service;

use PHPUnit\Framework\TestCase;
use Pvavilov\CalculateCommissions\Exception\ExchangeRateException;
use Pvavilov\CalculateCommissions\ValueObject\TransactionObject;
use Pvavilov\CalculateCommissions\Service\CommissionHandler;
use Pvavilov\CalculateCommissions\Service\BinlistClient;
use Pvavilov\CalculateCommissions\Service\ExchangeRateClient;
use Pvavilov\CalculateCommissions\Exception\BinlistException;

final class CommissionHandlerTest extends TestCase
{
    public function testCalculateNonEuCommission(): void
    {
        $binlistClient = $this->getMockBuilder(BinlistClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $binlistClient->method('getCountryAlpha2ByBin')
            ->willReturn('JP');

        $exchangeRateClient = $this->getMockBuilder(ExchangeRateClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $exchangeRateClient->method('getRateByCurrency')
            ->willReturn(157.640431);

        $commissionHandler = new CommissionHandler($binlistClient, $exchangeRateClient);
        $commission = $commissionHandler->calculate(new TransactionObject(11111, 1000, 'JPY'));

        $this->assertSame(0.13, $commission);
    }

    public function testCalculateEuCommission(): void
    {
        $binlistClient = $this->getMockBuilder(BinlistClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $binlistClient->method('getCountryAlpha2ByBin')
            ->willReturn('ES');

        $exchangeRateClient = $this->getMockBuilder(ExchangeRateClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $exchangeRateClient->method('getRateByCurrency')
            ->willReturn(157.640431);

        $commissionHandler = new CommissionHandler($binlistClient, $exchangeRateClient);
        $commission = $commissionHandler->calculate(new TransactionObject(11111, 1000, 'JPY'));

        $this->assertSame(0.07, $commission);
    }

    public function testCalculateExchangeRateException(): void
    {
        $this->expectException(ExchangeRateException::class);

        $binlistClient = $this->getMockBuilder(BinlistClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $binlistClient->method('getCountryAlpha2ByBin')
            ->willReturn('ES');

        $exchangeRateClient = $this->getMockBuilder(ExchangeRateClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $exchangeRateClient->method('getRateByCurrency')
            ->will($this->throwException(new ExchangeRateException()));

        $commissionHandler = new CommissionHandler($binlistClient, $exchangeRateClient);
        $commissionHandler->calculate(new TransactionObject(11111, 1000, 'JPY'));
    }

    public function testBinlistException(): void
    {
        $this->expectException(BinlistException::class);

        $binlistClient = $this->getMockBuilder(BinlistClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $binlistClient->method('getCountryAlpha2ByBin')
            ->will($this->throwException(new BinlistException()));

        $exchangeRateClient = $this->getMockBuilder(ExchangeRateClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $exchangeRateClient->method('getRateByCurrency')
            ->willReturn(157.640431);

        $commissionHandler = new CommissionHandler($binlistClient, $exchangeRateClient);
        $commissionHandler->calculate(new TransactionObject(11111, 1000, 'JPY'));
    }

}
