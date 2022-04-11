<?php

namespace Tests\Repositories;

use App\Commands\CreateEntityCommand;
use App\Commands\CreateUserCommandHandler;
use App\config\SqlLiteConfig;
use App\Connections\ConnectorInterface;
use App\Drivers\Connection;
use App\Drivers\PdoConnectionDriver;
use App\Entities\User\User;
use App\Exceptions\UserEmailExistException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepository;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenUserNotFound(): void
    {
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);

        $repository = new UserRepository($this->getConnectionStub());

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User not found');

        $repository->getUserByEmail('fadeev@star2play.ru');
    }

    /**
     * @throws UserEmailExistException
     */
    public function testItSavesUserToDatabase(): void
    {
        $repository = new UserRepository($this->getConnectionStub());
        $createUserCommandHandler = new CreateUserCommandHandler($repository);

        $this->expectException(UserEmailExistException::class);
        $this->expectExceptionMessage('Пользователь с таким email уже существует в системе');

        $command = new CreateEntityCommand(
            new User(
                'Georgii',
                'Fadeev',
                'fadeev122@start2play.ru'
            )
        );

        $createUserCommandHandler->handle(
            $command
        );
    }

    private function getConnectionStub(): ConnectorInterface
    {
        return new class implements ConnectorInterface {

            public function getConnection(): Connection
            {
                return PdoConnectionDriver::getInstance(SqlLiteConfig::DSN);
            }
        };
    }

}