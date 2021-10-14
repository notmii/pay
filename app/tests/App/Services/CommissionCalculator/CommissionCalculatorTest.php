<?php

namespace Tests\App\Services\CommissionCalculator;

use Tests\TestCase;
use App\Services\CommissionCalculator\CommissionCalculator;

class CommissionCalculatorTest extends TestCase
{
    private $calculator;

    public function setUp(): void
    {
        parent::setUp();
        $this->calculator = $this->getMockBuilder(CommissionCalculator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_calculate'])
            ->getMock();
    }

    /**
     * @testdox should compute commission correctly
     * @return void
     */
    public function test_calculation_001(): void
    {
        $commission = 10.0019;
        $this->calculator
            ->method('_calculate')
            ->willReturn($commission);

        $userId = 1;
        $amount = 100;
        $currency = 'EUR';
        $date = '2021-01-01';

        $result = $this->calculator->calculate($userId, $date, $amount, $currency);
        $expected = round($commission, config('app.currency_decimal_places')[$currency]);

        $this->assertEquals($expected, $result);
    }

    /**
     * @testdox should throw an exception due to unsupported currency code
     * @return void
     */
    public function test_calculation_002(): void
    {
        $commission = 10.0019;
        $this->calculator
            ->method('_calculate')
            ->willReturn($commission);

        $userId = 1;
        $amount = 100;
        $currency = 'UNKNOWN_CURRENCY';
        $date = '2021-01-01';

        $this->expectExceptionMessage(sprintf('Unknown currency code %s.', $currency));

        $result = $this->calculator->calculate($userId, $date, $amount, $currency);
    }

    /**
     * @testdox should throw an exception due to invalid date
     * @return void
     */
    public function test_calculation_003(): void
    {
        $commission = 10.0019;
        $this->calculator
            ->method('_calculate')
            ->willReturn($commission);

        $userId = 1;
        $amount = 100;
        $currency = 'EUR';
        $date = '2021-01-42';

        $this->expectExceptionMessage(sprintf('Invalid date %s.', $date));

        $result = $this->calculator->calculate($userId, $date, $amount, $currency);
    }

    private function default($params = [])
    {
    }
}
