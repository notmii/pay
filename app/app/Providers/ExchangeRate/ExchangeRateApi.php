<?php

namespace App\Providers\ExchangeRate;

class ExchangeRateApi implements ExchangeRateProviderInterface
{
    public function convert($amount, $from, $to): float
    {
        return $amount;
    }
}
