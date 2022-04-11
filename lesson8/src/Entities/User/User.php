<?php

namespace App\Entities\User;

use App\Traits\Id;

class User implements UserInterface
{
    public const TABLE_NAME = 'User';

    use Id;

    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $email,
        private string $password,
    ) {}

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(string $password):string
    {
        $this->password = password_hash($password, null);
        return $this->password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function checkPassword(string $password):bool
    {
        return password_verify($password, $this->password);
    }

    public function __toString(): string
    {
        return sprintf(
            "[%d] %s %s %s",
            $this->getId(),
            $this->getFirstName(),
            $this->getLastName(),
            $this->getEmail()
        );
    }

    public function getTableName(): string
    {
        return static::TABLE_NAME;
    }


}