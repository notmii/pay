<?php

namespace App\Repositories;

use App\Library\Core\Entities\Operation;

interface OperationRepositoryInterface
{
    public function getUserOperations($userId, $weekNumber, $transactionType): array;
    public function storeUserOperation(Operation $operation): bool;
    public function getAllOperations(): array;
}
