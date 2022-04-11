<?php

namespace App\Http\Actions\User;

use App\Exceptions\HttpException;
use App\Exceptions\UserNotFoundException;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Repositories\UserRepositoryInterface;

class FindByEmail implements ActionInterface
{
    public function __construct(private UserRepositoryInterface $usersRepository) {}

    public function handle(Request $request): Response
    {
        try {
            $email = $request->query('email');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $user = $this->usersRepository->getUserByEmail($email);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'email' => $user->getEmail(),
            'name' => $user->getFirstName() . ' ' . $user->getLastName(),
        ]);
    }
}