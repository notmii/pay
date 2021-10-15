<?php

namespace App\Providers\Storages;

use App\Library\Core\Entities\Operation;

interface OperationRepositoryInterface
{
    public function getUserOperations($userId, $weekNumber): array;
    public function storeUserOperation(Operation $operation): string;
}
