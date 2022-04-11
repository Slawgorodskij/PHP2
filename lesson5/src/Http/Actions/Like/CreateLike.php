<?php

namespace App\Http\Actions\Like;

use App\Commands\CreateEntityCommand;
use App\Commands\CreateLikeCommandHandler;
use App\Entities\Like\Like;
use App\Exceptions\HttpException;
use App\Http\Request;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Response;
use App\Http\SuccessfulResponse;

class CreateLike implements ActionInterface
{
    public function __construct(
        private CreateLikeCommandHandler $createLikeCommandHandler
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $like = new Like(
                $request->jsonBodyField('author_id'),
                $request->jsonBodyField('article_id'),
            );
            $this->createLikeCommandHandler->handle(new CreateEntityCommand($like));
        } catch (HttpException $exception) {
            return new ErrorResponse($exception->getMessage());
        }
        return new SuccessfulResponse([
            'like' => 'Поставлен like',
        ]);
    }
}