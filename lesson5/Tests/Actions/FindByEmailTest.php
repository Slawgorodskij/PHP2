<?php

namespace Tests;

use App\config\SqlLiteConfig;
use App\Drivers\PdoConnectionDriver;
use App\Entities\User\User;
use App\Exceptions\UserNotFoundException;
use App\Http\Actions\FindByEmail;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\SuccessfulResponse;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class FindByEmailTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsErrorResponseIfNoEmailProvided(): void
    {
        $request = new Request([], [], '');
        $userRepository = $this->getUserRepository([]);

        $action = new FindByEmail($userRepository);
        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString(
            '{"success":false,"reason":"No such query param in the request: email"}'
        );

        $response->send();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsErrorResponseIfUserNotFound(): void
    {
        $request = new Request(['email' => 'fadee123v@start2play'], [], '');

        $usersRepository = $this->getUserRepository([]);
        $action = new FindByEmail($usersRepository);

        $response = $action->handle($request);
        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString('{"success":false,"reason":"Not found"}');
        $response->send();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request(['email' => 'fadeev@start2play.ru'], [], '');

        $usersRepository = $this->getUserRepository([
            new User(
                'Georgii',
                'Fadeev',
                'fadeev@start2play.ru'
            ),
        ]);

        $action = new FindByEmail($usersRepository);
        $response = $action->handle($request);

        $this->assertInstanceOf(SuccessfulResponse::class, $response);
        $this->expectOutputString('{"success":true,"data":{"email":"fadeev@start2play.ru","name":"Georgii Fadeev"}}');

        $response->send();
    }

    private function getUserRepository(array $users): UserRepositoryInterface
    {
        return new class($users) implements UserRepositoryInterface {

            public function __construct(
                private array $users
            ) {
            }

            public function save(User $user): void
            {
            }

            public function get(int $id): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getUserByEmail(string $email): User
            {
                foreach ($this->users as $user) {
                    if ($user instanceof User && $email === $user->getEmail()) {
                        return $user;
                    }
                }

                throw new UserNotFoundException("Not found");
            }
        };
    }
}