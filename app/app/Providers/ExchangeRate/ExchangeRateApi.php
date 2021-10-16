<?php

namespace App\Providers\ExchangeRate;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;

class ExchangeRateApi implements ExchangeRateProviderInterface
{
    const BASE_URL = 'http://api.exchangeratesapi.io/v1/latest';
    private $logger;
    private $rates;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->setLogger($logger);
    }

    public function setLogger(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        return $this;
    }

    public function convert($amount, $from, $to): float
    {
        $rates = $this->getLatestExchangeRate();
        $result = $amount;
        $from = strtoupper($from);
        $to = strtoupper($to);

        if (!isset($rates[$to])) {
            throw new \Exception(
                sprintf('ExchangeRate: Missing exchange rate info for currency (%s)', $to)
            );
        }

        if (!isset($rates[$from])) {
            throw new \Exception(
                sprintf('ExchangeRate: Missing exchange rate info for currency (%s)', $from)
            );
        }

        if ($to === 'EUR') {
            $result = $amount / $rates[$from];
        } else if ($from === 'EUR') {
            $result = $amount * $rates[$to];
        } else {
            $fromEur = $amount / $rates[$from];
            $result = $fromEur * $rates[$to];
        }

        return $result;
    }

    private function getLatestExchangeRate()
    {
        if ($this->rates) {
            return $this->rates;
        }

        $response = $this->sendRequest();

        if ($response) {
            $this->rates = $response['rates'];
        }

        return $this->rates;
    }

    protected function sendRequest(
        $url = null,
        $params = []
    ) {
        $result = false;
        try {
            $timestamp = time();
            $response = Http::get(
                static::BASE_URL,
                array_merge($params, [ 'access_key' => config('app.exchange_rate_api_key') ])
            );
            $result = json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $ex) {
            $this->logger->error(
                spintf('Error in fetching from Exchange Rate API with error: %s', $ex->getMessage()),
                [
                    'exception' => $ex,
                    'url' => $url,
                    'params' => $params
                ]
            );
            $result = false;
        }

        return $result;
    }
}
