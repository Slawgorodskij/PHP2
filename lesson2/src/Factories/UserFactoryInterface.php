<?php

namespace App\Factories;

use App\Entities\User\UserInterface;

interface UserFactoryInterface extends FactoryInterface
{
    public function create(): UserInterface;
}