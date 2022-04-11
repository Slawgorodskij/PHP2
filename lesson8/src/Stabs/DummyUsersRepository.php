<?php

namespace App\Stabs;

use App\Entities\EntityInterface;
use App\Entities\User\User;
use App\Repositories\UserRepositoryInterface;

class DummyUsersRepository implements UserRepositoryInterface
{

    public function get(int $id): EntityInterface
    {
        // TODO: Implement get() method.
    }

    public function getUserByEmail(string $email): User
    {
        return new User(
            uniqid().'user123',
            uniqid()."user123",
            uniqid().'user123@mail.ru'
        );
    }
}