<?php

namespace App\Factories;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector;
use App\Entities\User\User;
use App\Repositories\EntityRepositoryInterface;
use App\Repositories\UserRepository;
use JetBrains\PhpStorm\Pure;

class RepositoryFactory implements RepositoryFactoryInterface
{
    private ConnectorInterface $connector;

    public function __construct(ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqlLiteConnector();
    }

    public function create(string $entityType): EntityRepositoryInterface
    {
        return match ($entityType)
        {
            User::class => new UserRepository($this->connector),
        };
    }

}