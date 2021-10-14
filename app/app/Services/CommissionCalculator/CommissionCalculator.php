<?php

namespace App\Services\CommissionCalculator;
use \DateTime;

abstract class CommissionCalculator
{
    public function calculate($userId, $date, $amount, $currencyCode) : float
    {
        if (!isset(config('app.currency_decimal_places')[$currencyCode])) {
            throw new \Exception(sprintf('Unknown currency code %s.', $currencyCode));
        }

        $timestamp = strtotime($date);

        if (!$timestamp) {
            throw new \Exception(sprintf('Invalid date %s.', $date));
        }

        $parsedDate = (new DateTime())
            ->setTimestamp($timestamp);

        $precission = config('app.currency_decimal_places')[$currencyCode];
        $commission = $this->_calculate(
            $parsedDate,
            $userId,
            $amount,
            $currencyCode
        );

        return round($commission, $precission);
    }

    abstract protected function _calculate(DateTime $date, $userId, $amount, $currencyCode) : float;
}
