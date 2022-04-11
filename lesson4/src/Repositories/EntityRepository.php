<?php

namespace App\Repositories;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector;
use App\Drivers\Connection;
use App\Entities\EntityInterface;

abstract class EntityRepository implements EntityRepositoryInterface
{
    protected Connection $connection;
    private ?ConnectorInterface $connector;

    public function __construct(ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqlLiteConnector();
        $this->connection = $this->connector->getConnection();
    }

    abstract public function get(int $id): EntityInterface;
}