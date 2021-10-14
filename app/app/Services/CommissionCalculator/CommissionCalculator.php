<?php

namespace App\Services\CommissionCalculator;

abstract class CommissionCalculator
{
    public function calculate($userId, $date, $amount, $currencyCode) : float
    {
        if (!isset(config('app.currency_decimal_places')[$currencyCode])) {
            throw new \Exception(sprintf('Unknown currency code %s.', $currencyCode));
        }

        $parsedDate = strtotime($date);

        if (!$parsedDate) {
            throw new \Exception(sprintf('Invalid date %s.', $date));
        }

        $precission = config('app.currency_decimal_places')[$currencyCode];
        $commission = $this->_calculate($date, $userId, $amount, $currencyCode);
        return round($commission, $precission);
    }

    abstract protected function _calculate($date, $userId, $amount, $currencyCode) : float;
}
