<?php

namespace App\Services\CommissionCalculator;

use \DateTime;

/**
 * Class responsible for computing deposit commission
 */
class Deposit extends CommissionCalculator
{
    const COMMISSION_PERCENT = 0.003; // 0.3%

    protected function _calculate(
        DateTime $date,
        $userId,
        $amount,
        $currency
    ) : float {
        return $amount * static::COMMISSION_PERCENT;
    }
}
