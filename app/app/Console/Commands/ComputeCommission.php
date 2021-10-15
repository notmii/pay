<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Providers\ExchangeRate\ExchangeRateProviderInterface;
use App\Services\CommissionCalculator\CommissionCalculatorFactory;
use App\Repositories\OperationRepositoryInterface;
use App\Library\Core\Entities\Operation;

class ComputeCommission extends Command
{
    protected $signature = 'compute:commissions';
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
        $operations = [
            [ '2014-12-31', 4, 'private', 'withdraw', 1200.00, 'EUR'], 
        ];

        foreach ($operations as $row) {
            $operation = (new Operation())
                ->setUserId($row[1])
                ->setTransactionDate($row[0])
                ->setAmount($row[4])
                ->setEurAmount($row[4])
                ->setCurrencyCode($row[5])
                ->setTransactionType($row[3])
                ->setClientType($row[2]);

            if (strtoupper($operation->getCurrencyCode()) !== 'EUR') {
                $eurAmount = $this->exchangeRateProvider->convert(
                    $operation->getAmount(),
                    $operation->getCurrencyCode(),
                    'EUR'
                );
                $operation->setEurAmount($eurAmount);
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

            $this->operationRepository->storeUserOperation($operation);

            echo sprintf("%s\n", $commission);
        }

        echo "\n";

        var_dump($this->operationRepository->getAllOperations());

        return 0;
    }
}
