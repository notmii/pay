<?php

namespace App\Providers\ExchangeRate;

interface ExchangeRateProviderInterface
{
    public function convert($amount, $fromCurrency, $toCurrency) : float;
}
