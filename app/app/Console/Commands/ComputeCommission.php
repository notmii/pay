<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Providers\ExchangeRate\ExchangeRateProviderInterface;
use App\Services\CommissionCalculator\CommissionCalculatorFactory;
use App\Repositories\OperationRepositoryInterface;
use App\Library\Core\Entities\Operation;

class ComputeCommission extends Command
{
    protected $signature = 'compute:commissions {--csv= : Absolute file path for the CSV file};';
    protected $description = 'Compute commissions';

    private $commissionCalculatorFactory;
    private $operationRepository;
    private $exchangeRateProvider;

    public function __construct(
        CommissionCalculatorFactory $factory,
        OperationRepositoryInterface $operationRepository,
        ExchangeRateProviderInterface $exchangeRateProvider
    ) {
        parent::__construct();
        $this->commissionCalculatorFactory = $factory;
        $this->operationRepository = $operationRepository;
        $this->exchangeRateProvider = $exchangeRateProvider;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $csv = file($this->option('csv'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (!$csv) {
                echo sprintf('File provided can\'t be read (%s)', $this->option('csv'));
                return 0;
            }

            $operations = array_map('str_getcsv', $csv);

            foreach ($operations as $row) {
                $operation = (new Operation())
                    ->setUserId($row[1])
                    ->setTransactionDate($row[0])
                    ->setAmount((float)$row[4])
                    ->setEurAmount((float)$row[4])
                    ->setCurrencyCode($row[5])
                    ->setTransactionType($row[3])
                    ->setClientType($row[2]);

                if (strtoupper($operation->getCurrencyCode()) !== 'EUR') {
                    $eurAmount = $this->exchangeRateProvider->convert(
                        $operation->getAmount(),
                        $operation->getCurrencyCode(),
                        'EUR'
                    );
                    $operation->setEurAmount((float)$eurAmount);
                }

                $commission = $this->commissionCalculatorFactory
                    ->getCalculator(
                        $operation->getTransactionType(),
                        $operation->getClientType()
                    )
                    ->calculate(
                        $operation->getUserId(),
                        $operation->getTransactionDate(),
                        $operation->getAmount(),
                        $operation->getCurrencyCode()
                    );

                $commission = number_format(
                    $commission,
                    config('app.currency_decimal_places')[$operation->getCurrencyCode()],
                    '.',
                    ''
                );

                $this->operationRepository->storeUserOperation($operation);

                echo sprintf("%s\n", $commission);
            }
            echo "\n";
            return 0;
        } catch (\Exception $ex) {
            echo sprintf('Unknown error occured (%s)', $ex->getMessage());
        }
    }
}
