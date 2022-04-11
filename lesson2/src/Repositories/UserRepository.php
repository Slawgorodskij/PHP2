<?php

namespace App\Repositories;


use App\Entities\EntityInterface;
use App\Entities\User\User;
use App\Exceptions\UserNotFoundException;
use App\Factories\EntityRepository;
use PDO;
use PDOStatement;

class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    /**
     * @param EntityInterface $entity
     * @return void
     */
    public function save(EntityInterface $entity):void
    {

        /**
         * @var User $entity
         */
        $statement =  $this->connector->getConnection()
            ->prepare("INSERT INTO users (first_name, last_name) VALUES (:first_name, :last_name)");

        $statement->execute(
            [
                ':firstName' => $entity->getFirstName(),
                ':last_name' => $entity->getLastName(),
            ]
        );
    }

    /**
     * @throws UserNotFoundException
     */
    public function get(int $id): User
    {
        $statement = $this->connector->getConnection()->prepare(
            'SELECT * FROM users WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        return $this->getUser($statement, $user);
    }

    /**
     * @throws UserNotFoundException
     */
    private function getUser(PDOStatement $statement, User $user): User
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            throw new UserNotFoundException(
                sprintf("Cannot find user: %s %s", $user->getFirstName(), $user->getLastName()));
        }

        return $user;
    }
}