<?php

namespace Tests\App\Services\CommissionCalculator;

use Tests\TestCase;
use App\Services\CommissionCalculator\BusinessWithdrawal;

class BusinessWithdrawalTest extends TestCase
{
    private $calculator;

    public function setUp(): void
    {
        parent::setUp();
        $this->calculator = new BusinessWithdrawal();
    }

    /**
     * @testdox should compute business withdrawal commission correctly
     * @return void
     */
    public function test_calculate_001(): void
    {
        $userId = 1;
        $amount = 100;
        $currency = 'EUR';
        $date = '2021-01-01';

        $result = $this->calculator->calculate($userId, $date, $amount, $currency);
        $expected = $amount * $this->calculator::COMMISSION_PERCENT;

        $this->assertEquals($expected, $result);
    }
}
