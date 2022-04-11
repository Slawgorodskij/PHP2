<?php

namespace App\Repositories;

use App\Entities\EntityInterface;
use App\Entities\User\User;

interface EntityRepositoryInterface
{
    public function save(EntityInterface $entity);
    public function get(int $id): EntityInterface;
}