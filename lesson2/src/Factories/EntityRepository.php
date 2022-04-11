<?php

namespace App\Factories;

use App\Connections\ConnectorInterface;
use App\Entities\EntityInterface;
use App\Entities\User\User;
use App\Repositories\EntityRepositoryInterface;

abstract class EntityRepository implements EntityRepositoryInterface
{
    protected ConnectorInterface $connector;

    public function __construct(ConnectorInterface $connector)
    {
        $this->connector = $connector;
    }

    abstract public function save(EntityInterface $entity):void;
    abstract public function get(int  $id): EntityInterface;
}