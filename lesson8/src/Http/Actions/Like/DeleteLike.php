<?php

namespace App\Http\Actions\Like;

use App\Commands\DeleteLikeCommandHandler;
use App\Exceptions\HttpException;
use App\Exceptions\LikeNotFoundException;
use App\Http\Actions\User\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Repositories\LikeRepository;

class DeleteLike implements ActionInterface
{
    public function __construct(
        private DeleteLikeCommandHandler $deleteLikeCommandHandler,
        private LikeRepository           $likeRepository,
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $like_id = $request->query('id');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $this->likeRepository->get($like_id);
        } catch (LikeNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $this->deleteLikeCommandHandler->handle($like_id);
        } catch (LikeNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'like' => 'Like удален',
        ]);
    }
}