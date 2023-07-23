<?php

require __DIR__ . '/vendor/autoload.php';

use Pvavilov\CalculateCommissions\ValueObject\TransactionObject;
use Pvavilov\CalculateCommissions\Service\CommissionHandler;
use Pvavilov\CalculateCommissions\Service\BinlistClient;
use Pvavilov\CalculateCommissions\Service\ExchangeRateClient;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$commissionHandler = new CommissionHandler(
    new BinlistClient(),
    new ExchangeRateClient($_ENV['EXCHANGE_RATE_API_KEY']),
);

$file = new SplFileObject($argv[1]);
while (!$file->eof()) {
    $transaction = json_decode($file->fgets());
    try {
        $commission = $commissionHandler->calculate(new TransactionObject(
            (int) $transaction->bin,
            (float) $transaction->amount,
            (string) $transaction->currency,
        ));
        echo "$commission\n";
    } catch (\Throwable $e) {
        echo "{$e->getMessage()}\n";
    }
}
