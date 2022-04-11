<?php

namespace App\Factories;

use App\Entities\User\User;
use App\Entities\User\UserInterface;

final class UserFactory extends Factory implements UserFactoryInterface
{
    public function create(): UserInterface
    {
        return new User(
            $this->faker->randomDigitNot(0),
            $this->faker->firstName(),
            $this->faker->lastName(),
        );
    }
}