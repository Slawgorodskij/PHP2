<?php

namespace App\Http\Actions\Comment;

use App\Commands\CreateCommentCommandHandler;
use App\Commands\CreateEntityCommand;
use App\Exceptions\HttpException;
use App\Entities\Comment\Comment;
use App\Http\Request;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Response;
use App\Http\SuccessfulResponse;

class CreateComment implements ActionInterface
{
    public function __construct(
        private CreateCommentCommandHandler $createCommentCommandHandler
    ){}

    public function handle(Request $request): Response
    {
        try{
            $comment = new Comment(
                $request->jsonBodyField('author_id'),
                $request->jsonBodyField('article_id'),
                $request->jsonBodyField('text'),
            );
            $this->createCommentCommandHandler->handle(new CreateEntityCommand($comment));
        }catch (HttpException $exception)
        {
            return new ErrorResponse($exception->getMessage());
        }
        return new SuccessfulResponse([
            'comment'=>$comment->gettext(),
        ]);
    }
}