<?php
namespace App\Repositories;

use App\Library\Core\Entities\Operation;

class OperationRepository implements OperationRepositoryInterface
{
    private $operations = [];

    public function getUserOperations($userId, $weekNumber): array
    {
        return [];
    }

    public function storeUserOperation(Operation $operation): bool
    {
        $timestamp = strtotime($operation->getTransactionDate());
        $parsedDate = (new \DateTime())
            ->setTimestamp($timestamp);
        $weekNumber = $parsedDate->format('YW');

        if (!array_key_exists((string)$operation->getUserId(), $this->operations)) {
            $this->operations[(string)$operation->getUserId()] = [
                'deposits' => [],
                'withdrawals' => [],
            ];
        }

        $transactionStorage = $operation->getTransactionType() === 'deposit' ?
            'deposits' : 'withdrawals';

        $storage = $this->operations[(string)$operation->getUserId()][$transactionStorage];
        if (!array_key_exists($weekNumber, $storage)) {
            $storage[$weekNumber] = [];
        }

        $storage[$weekNumber][] = $operation;

        return true;
    }
    
    public function getAllOperations(): arary
    {
        return $this->operations;
    }
}
