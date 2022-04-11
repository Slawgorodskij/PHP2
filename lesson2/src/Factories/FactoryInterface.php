<?php

namespace App\Factories;

use App\Entities\EntityInterface;

interface FactoryInterface
{
    public function create(): EntityInterface;
}