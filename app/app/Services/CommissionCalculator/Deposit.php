<?php

namespace App\Services\CommissionCalculator;

class Deposit extends CommissionCalculator
{
    const COMMISSION_PERCENT = 0.003; // 0.3%

    protected function _calculate($date, $userId, $amount, $currency) : float
    {
        return $amount * static::COMMISSION_PERCENT;
    }
}
