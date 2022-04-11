<?php

namespace App\Http\Auth;

use App\Entities\User\User;
use App\Exceptions\AuthException;
use App\Exceptions\HttpException;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\UserNotFoundException;
use App\Http\Request;
use App\Repositories\UserRepositoryInterface;

class JsonBodyUserIdIdentification implements IdentificationInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {
    }

    public function getUser(Request $request): User
    {
        try {
            $userId = $request->jsonBodyField('author_id');
        } catch (HttpException|InvalidArgumentException $e) {
            throw new AuthException($e->getMessage());
        }
        try {
            return $this->userRepository->findById($userId);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }
    }
}