<?php
namespace App\Repositories;

use App\Library\Core\Entities\Operation;

class OperationRepository implements OperationRepositoryInterface
{
    private $operations = [];

    public function getUserOperations($userId, $weekNumber, $transactionType): array
    {
        $transactionStorage = $transactionType === 'deposit' ?
            'deposits' : 'withdrawals';

        if (!isset($this->operations[$userId])) {
            return [];
        }

        if (!isset($this->operations[$userId][$transactionStorage][$weekNumber])) {
            return [];
        }

        return $this->operations[$userId][$transactionStorage][$weekNumber];
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

        $storage = &$this->operations[(string)$operation->getUserId()][$transactionStorage];
        if (!array_key_exists($weekNumber, $storage)) {
            $storage[$weekNumber] = [];
        }

        $storage[$weekNumber][] = $operation;

        return true;
    }

    public function getAllOperations(): array
    {
        return $this->operations;
    }
}
