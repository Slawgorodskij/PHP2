<?php

namespace App\Factories;

use App\Entities\EntityInterface;

interface EntityFactoryInterface
{
    public function create(string $type): EntityInterface;
}