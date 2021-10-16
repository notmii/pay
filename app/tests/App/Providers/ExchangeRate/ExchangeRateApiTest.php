<?php

namespace Tests\App\Providers\ExchangeRate;

use Tests\TestCase;
use App\Providers\ExchangeRate\ExchangeRateApi;
use Psr\Log\LoggerInterface;
use Illuminate\Support\Facades\Http;

class ExchangeRateApiTest extends TestCase
{
    private $api;

    public function setUp(): void
    {
        parent::setUp();
        $this->api = new ExchangeRateApi(
            $this->mockLogger()
        );
    }

    /**
     * @testdox should compute currency exchange to EUR, correctly
     * @return void
     */
    public function test_convert_001(): void
    {
        $httpResponse = [
            "success" => true,
            "timestamp" => 1634339164,
            "base" => "EUR",
            "date" => "2021-10-15",
            'rates' => [
                'JPY' => 10,
                'EUR' => 1,
                'AED' => 103.10
            ]
        ];

        Http::fake([
            sprintf('%s*', ExchangeRateApi::BASE_URL) => Http::response($httpResponse, 200)
        ]);

        $amount = 100;
        $expected = $amount * 10;

        $result = $this->api->convert($amount, 'EUR', 'JPY');
        $this->assertEquals($expected, $result);
    }

    /**
     * @testdox should compute currency exchange from EUR, correctly
     * @return void
     */
    public function test_convert_002(): void
    {
        $httpResponse = [
            "success" => true,
            "timestamp" => 1634339164,
            "base" => "EUR",
            "date" => "2021-10-15",
            'rates' => [
                'JPY' => 10,
                'EUR' => 1,
                'AED' => 103.10
            ]
        ];

        Http::fake([
            sprintf('%s*', ExchangeRateApi::BASE_URL) => Http::response($httpResponse, 200)
        ]);

        $amount = 100;
        $expected = $amount / 10;

        $result = $this->api->convert($amount, 'JPY', 'EUR');
        $this->assertEquals($expected, $result);
    }

    /**
     * @testdox should compute currency exchange from/to non-EUR currency, correctly
     * @return void
     */
    public function test_convert_003(): void
    {
        $httpResponse = [
            "success" => true,
            "timestamp" => 1634339164,
            "base" => "EUR",
            "date" => "2021-10-15",
            'rates' => [
                'JPY' => 10,
                'EUR' => 1,
                'AED' => 103.10
            ]
        ];

        Http::fake([
            sprintf('%s*', ExchangeRateApi::BASE_URL) => Http::response($httpResponse, 200)
        ]);

        $amount = 100;

        $fromEur = $amount / 10;
        $expected = $fromEur * 103.10;

        $result = $this->api->convert($amount, 'JPY', 'AED');
        $this->assertEquals($expected, $result);
    }

    private function mockLogger($params = [])
    {
        $mock = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'error',
                'emergency',
                'alert',
                'critical',
                'warning',
                'notice',
                'info',
                'debug',
                'log'
            ])
            ->getMock();
        return $mock;
    }
}
