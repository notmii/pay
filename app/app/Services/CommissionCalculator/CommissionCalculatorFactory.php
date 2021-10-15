<?php

namespace App\Services\CommissionCalculator;

use App\Services\CommissionCalculator\BusinessWithdrawal;
use App\Services\CommissionCalculator\Deposit;
use App\Services\CommissionCalculator\PrivateWithdrawal;

class CommissionCalculatorFactory
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function getCalculator(
        $transactionType,
        $clientType
    ) {
        switch ($transactionType) {
          case 'deposit':
              return $this->app->make(Deposit::class);
              break;

          case 'withdraw':
                if ($clientType === 'business') {
                    return $this->app->make(BusinessWithdrawal::class);
                }

                if ($clientType === 'private') {
                    return $this->app->make(PrivateWithdrawal::class);
                }
        }
    }
}
