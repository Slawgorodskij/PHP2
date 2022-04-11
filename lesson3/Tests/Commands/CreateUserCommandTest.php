<?php

namespace Tests\Commands;

use App\Commands\CreateEntityCommand;
use App\Commands\CreateUserCommandHandler;
use App\Entities\EntityInterface;
use App\Entities\User\User;
use App\Exceptions\UserEmailExistException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepositoryInterface;
use App\Stabs\DummyUsersRepository;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{
    public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
    {
        $createUserCommandHandler = new CreateUserCommandHandler(new DummyUsersRepository());

        $this->expectException(UserEmailExistException::class);
        $this->expectExceptionMessage('Пользователь с таким email уже существует в системе');

        $command = new CreateEntityCommand(
            new User(
                'Georgii',
                'Fadeev',
                'fadeev123@start2play.ru'
            )
        );

        $createUserCommandHandler->handle($command);
    }


    public function testItThrowsAnExceptionWhenUserAlreadyExistsByAnonymous(): void
    {
        $createUserCommandHandler = new CreateUserCommandHandler($this->makeUsersRepository());

        $this->expectException(UserEmailExistException::class);
        $this->expectExceptionMessage('Пользователь с таким email уже существует в системе');

        $command = new CreateEntityCommand(
            new User(
                'Georgii',
                'Fadeev',
                'fadeev123@start2play.ru'
            )
        );

        $createUserCommandHandler->handle($command);
    }

    private function makeUsersRepository(): UserRepositoryInterface
    {
        return new class implements UserRepositoryInterface {

            public function get(int $id): EntityInterface
            {
                throw new UserNotFoundException("Not found");
            }

            public function getUserByEmail(string $email): User
            {
                return new User('name', 'name', 'fadeev123@start2play.ru');
            }
        };
    }

    public function testItSavesUserToRepository(): void
    {
        $usersRepository = new class implements UserRepositoryInterface {

            private bool $called = false;

            public function get(int $id): EntityInterface
            {
                throw new UserNotFoundException("Not found");
            }

            public function getUserByEmail(string $email): User
            {
                $this->called = true;
                return new User('name', 'name', 'fadeev123@start2play.ru');
            }

            public function wasCalled(): bool
            {
                return $this->called;
            }
        };

        $createUserCommandHandler = new CreateUserCommandHandler($usersRepository);

        $this->expectException(UserEmailExistException::class);
        $this->expectExceptionMessage('Пользователь с таким email уже существует в системе');

        $command = new CreateEntityCommand(
            new User(
                'Georgii',
                'Fadeev',
                'fadeev123@start2play.ru'
            )
        );

        $createUserCommandHandler->handle($command);
        $this->assertTrue($usersRepository->wasCalled());
    }
}