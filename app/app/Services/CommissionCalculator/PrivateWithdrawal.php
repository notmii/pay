<?php

namespace App\Services\CommissionCalculator;

use \DateTime;
use App\Providers\ExchangeRate\ExchangeRateProviderInterface;
use App\Providers\Storages\OperationRepositoryInterface;

class PrivateWithdrawal extends CommissionCalculator
{
    const FREE_OF_CHARGE_AMOUNT = 1000;
    const FREE_OF_CHARGE_CURRENCY = 'EUR';
    const FREE_OF_CHARGE_COUNT = 3;
    const COMMISSION_PERCENT = 0.003; // 0.3%

    private $exchangeRateProvider;
    private $operation;

    public function __construct(
        ExchangeRateProviderInterface $exchangeRateProvider,
        OperationRepositoryInterface $operation
    ) {
        $this->exchangeRateProvider = $exchangeRateProvider;
        $this->operationRepository = $operation;
    }

    protected function _calculate(DateTime $date, $userId, $amount, $currencyCode) : float
    {
        $operations = $this->operationRepository->getUserOperations(
            $userId,
            $date->format('YW')
        );

        if (count($operations) >= static::FREE_OF_CHARGE_COUNT) {
            return $amount * static::COMMISSION_PERCENT;
        }

        if ($currencyCode !== static::FREE_OF_CHARGE_CURRENCY) {
            $amount = $this->exchangeRateProvider->convert(
                $amount,
                $currencyCode,
                static::FREE_OF_CHARGE_CURRENCY
            );
        }

        // Compute commission for operations that are less than or equal to 3
        // for a given period of time.
        $totalWithdrawal = array_reduce($operations, function($carry, $operation) {
            return $carry += $operationA->getEurAmount();
        });

        $deltaWithdrawal = ($totalWithdrawal + $amount);

        // Free of commission for less than 1000 eur.
        if ($deltaWithdrawal <= static::FREE_OF_CHARGE_AMOUNT) {
            return 0;
        }

        $commissionableAmount = $deltaWithdrawal - static::FREE_OF_CHARGE_AMOUNT;

        $commission = $this->exchangeRateProvider->convert(
            $commissionableAmount * static::COMMISSION_PERCENT,
            static::FREE_OF_CHARGE_CURRENCY,
            $currencyCode
        );

        return $commission;
    }
}
