<?php

namespace App\Http\Actions\Comment;

use App\Commands\DeleteCommentCommandHandler;
use App\Exceptions\CommentNotFoundException;
use App\Exceptions\HttpException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Repositories\CommentRepository;

class DeleteComment implements ActionInterface
{
    public function __construct(
        private ?DeleteCommentCommandHandler $deleteCommentCommandHandler = null,
        private ?CommentRepository           $commentRepository = null,
    )
    {
        $this->deleteCommentCommandHandler =
            $this->deleteCommentCommandHandler ??
            new DeleteCommentCommandHandler();
        $this->commentRepository =
            $this->commentRepository ??
            new CommentRepository();
    }

    public function handle(Request $request): Response
    {
        try {
            $comment_id = $request->query('id');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $this->commentRepository->get($comment_id);
        } catch (CommentNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $this->deleteCommentCommandHandler->handle($comment_id);
        } catch (CommentNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'comment' => 'Комментарий удален',
        ]);
    }
}