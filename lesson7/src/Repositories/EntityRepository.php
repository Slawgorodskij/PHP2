<?php

namespace App\Repositories;

use App\Drivers\Connection;
use App\Entities\EntityInterface;

abstract class EntityRepository implements EntityRepositoryInterface
{
    public function __construct(protected Connection $connection){}

    abstract public function findById(int  $id): EntityInterface;
}