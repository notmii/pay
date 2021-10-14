<?php

namespace Tests\App\Services\CommissionCalculator;

use Tests\TestCase;
use App\Services\CommissionCalculator\PrivateWithdrawal;
use App\Providers\ExchangeRate\ExchangeRateProviderInterface;
use App\Operation;

class PrivateWithdrawalTest extends TestCase
{
    private $calculator;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @testdox should return 0 commission due to user's first operation
     * @return void
     */
    public function test_calculate_001(): void
    {
        $exchangeRateProvider = $this->mockExchangeRateProvider();
        $operation = $this->mockOperation();

        $calculator = new PrivateWithdrawal(
            $exchangeRateProvider,
            $operation
        );

        $operation->method('getUserOperations')
            ->willReturn([]);

        $userId = 1;
        $amount = 100;
        $currency = 'EUR';
        $date = '2021-01-01';

        $result = $calculator->calculate($userId, $date, $amount, $currency);
        $expected = 0;

        $this->assertEquals($expected, $result);
    }

    /**
     * @testdox should return 0.3 due to excess of 1000 eur.
     * @return [type] [description]
     */
    public function test_calculate_002(): void
    {
        $exchangeRateProvider = $this->mockExchangeRateProvider();
        $operation = $this->mockOperation();

        $calculator = new PrivateWithdrawal(
            $exchangeRateProvider,
            $operation
        );
        
        $exchangeRateProvider->method('convert')
            ->willReturnCallback(function($amount, $from, $to) {
                return $amount * 1;
            });

        $operation->method('getUserOperations')
            ->willReturn([]);

        $userId = 1;
        $amount = 1100;
        $currency = 'EUR';
        $date = '2021-01-01';

        $result = $calculator->calculate($userId, $date, $amount, $currency);
        $expected = ($amount - $calculator::FREE_OF_CHARGE_AMOUNT) * $calculator::COMMISSION_PERCENT;

        $this->assertEquals($expected, $result);
    }

    /**
     * @testdox should return 3 due to 4th withdrawal operation.
     * @return [type] [description]
     */
    public function test_calculate_003(): void
    {
        $exchangeRateProvider = $this->mockExchangeRateProvider();
        $operation = $this->mockOperation();

        $calculator = new PrivateWithdrawal(
            $exchangeRateProvider,
            $operation
        );

        $exchangeRateProvider->method('convert')
            ->willReturnCallback(function($amount, $from, $to) {
                return $amount * 1;
            });

        $operation->method('getUserOperations')
            ->willReturn([new Operation(), new Operation(), new Operation()]);

        $userId = 1;
        $amount = 100;
        $currency = 'EUR';
        $date = '2021-01-01';

        $result = $calculator->calculate($userId, $date, $amount, $currency);
        $expected = $amount * $calculator::COMMISSION_PERCENT;

        $this->assertEquals($expected, $result);
    }

    private function mockExchangeRateProvider()
    {
        $mock = $this->getMockBuilder(ExchangeRateProviderInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['convert'])
            ->getMock();
        return $mock;
    }

    private function mockOperation()
    {
        $mock = $this->getMockBuilder(Operation::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getUserOperations'])
            ->getMock();
        return $mock;
    }
}
