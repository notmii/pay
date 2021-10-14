<?php

namespace App\Services\CommissionCalculator;

use \DateTime;

class BusinessWithdrawal extends CommissionCalculator
{
    const COMMISSION_PERCENT = 0.005; // 0.5%

    protected function _calculate(
        DateTime $date,
        $userId,
        $amount,
        $currencyCode
    ) : float {
        return $amount * static::COMMISSION_PERCENT;
    }
}
