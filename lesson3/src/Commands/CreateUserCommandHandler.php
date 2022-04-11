<?php

namespace App\Commands;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector;
use App\Entities\User\User;
use App\Exceptions\UserEmailExistException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepositoryInterface;

class CreateUserCommandHandler implements CommandHandlerInterface
{
    private \PDOStatement|false $stmt;

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ?ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqlLiteConnector();
        $this->stmt = $this->connector->getConnection()->prepare($this->getSQL());
    }

    /**
     * @throws UserEmailExistException
     * @var CreateEntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        /**
         * @var User $user
         */
        $user = $command->getEntity();
        $email = $user->getEmail();


        if(!$this->isUserExists($email))
        {
            $this->stmt->execute(
                [
                    ':firstName' => $user->getFirstName(),
                    ':lastName' => $user->getLastName(),
                    ':email' => $email,
                ]
            );
        }
        else
        {
            throw new UserEmailExistException();
        }
    }

    private function isUserExists(string $email): bool
    {
        try {
            $this->userRepository->getUserByEmail($email);
        } catch (UserNotFoundException) {
            return false;
        }

        return true;
    }

    public function getSQL(): string
    {
        return "INSERT INTO User (first_name, last_name, email) 
        VALUES (:firstName, :lastName, :email)";
    }
}
