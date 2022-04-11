<?php

namespace App\Repositories;

use App\Connections\ConnectorInterface;
use App\Drivers\Connection;
use App\Entities\EntityInterface;

abstract class EntityRepository implements EntityRepositoryInterface
{
    protected Connection $connection;

    public function __construct(ConnectorInterface $connector)
    {
        $this->connection = $connector->getConnection();
    }

    abstract public function get(int  $id): EntityInterface;
}