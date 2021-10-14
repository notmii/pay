<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $table = 'operation';
    protected $primaryKey = 'id';
    public $incrementing = true;

    public function getUserOperations($userId, $weekNumber): Array
    {
        return [];
    }
}
