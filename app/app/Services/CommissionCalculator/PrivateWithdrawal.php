<?php

namespace App\Services\CommissionCalculator;

use \DateTime;
use App\Providers\ExchangeRate\ExchangeRateProviderInterface;
use App\Repositories\OperationRepositoryInterface;

class PrivateWithdrawal extends CommissionCalculator
{
    const FREE_OF_CHARGE_AMOUNT = 1000;
    const FREE_OF_CHARGE_CURRENCY = 'EUR';
    const FREE_OF_CHARGE_COUNT = 3;
    const COMMISSION_PERCENT = 0.003; // 0.3%

    private $exchangeRateProvider;
    private $operationRepository;

    public function __construct(
        ExchangeRateProviderInterface $exchangeRateProvider,
        OperationRepositoryInterface $operationRepository
    ) {
        $this->exchangeRateProvider = $exchangeRateProvider;
        $this->operationRepository = $operationRepository;
    }

    /** 
     * Set the value of Exchange Rate Provider 
     * @param mixed $exchangeRateProvider
     * @return self
     */
    public function setExchangeRateProvider(
        ExchangeRateProviderInterface $exchangeRateProvider
    ) {
        $this->exchangeRateProvider = $exchangeRateProvider;
        return $this;
    }
 
    /** 
     * Set the value of Operation Repository
     * @param mixed $operation
     * @return self
     */
    public function setOperationRepository(
        OperationRepositoryInterface $operationRepository
    ) {
        $this->operationRepository = $operationRepository;
        return $this;
    }

    protected function _calculate(DateTime $date, $userId, $amount, $currencyCode) : float
    {
        $operations = $this->operationRepository->getUserOperations(
            $userId,
            $date->format('YW'),
            'withdrawal'
        );

        // if withdrawal count more the FREE COUNT
        // charge full price commision on withdrawal amount.
        if (count($operations) >= static::FREE_OF_CHARGE_COUNT) {
            return $amount * static::COMMISSION_PERCENT;
        }

        // To avoid confusion we will compute in EUR.
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
            return $carry += $operation->getEurAmount();
        });

        // if the past withdrawals already exceeded FREE AMOUNT
        // charge full price commission on withdrwal amount.
        if ($totalWithdrawal > static::FREE_OF_CHARGE_AMOUNT) {
            $commission = $this->exchangeRateProvider->convert(
                $amount * static::COMMISSION_PERCENT,
                static::FREE_OF_CHARGE_CURRENCY,
                $currencyCode
            );
            return $commission;
        }

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
