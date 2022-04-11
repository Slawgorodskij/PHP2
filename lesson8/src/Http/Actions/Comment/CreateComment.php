<?php

namespace App\Http\Actions\Comment;

use App\Commands\CreateCommentCommandHandler;
use App\Commands\CreateEntityCommand;
use App\Commands\EntityCommand;
use App\Entities\Article\ArticleInterface;
use App\Entities\Comment\Comment;
use App\Entities\Comment\CommentInterface;
use App\Exceptions\HttpException;
use App\Http\Actions\User\ActionInterface;
use App\Http\Auth\AuthenticationInterface;
use App\Http\AuthArticles\IdentificationArticleInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use Psr\Log\LoggerInterface;

class CreateComment implements ActionInterface
{
    public function __construct(
        private CreateCommentCommandHandler    $createCommentCommandHandler,
        private AuthenticationInterface        $authentication,
        private IdentificationArticleInterface $identificationArticle,
        private LoggerInterface                $logger,
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $comment = new Comment(
                $this->authentication->getUser($request),
                $this->identificationArticle->getArticle($request),
                $request->jsonBodyField('text'),
            );
            $comment = $this->createCommentCommandHandler->handle(new EntityCommand($comment));
        } catch (HttpException $e) {
            $this->logger->error($e);
            return new ErrorResponse($e->getMessage());
        }

        $data = [
            'id' => $comment->getId(),
            'author_id' => $comment->getAuthor()->getId(),
            'article' => $comment->getArticle()->getId(),
            'text' => $comment->getText(),
        ];

        $this->logger->info('Create new Comment', $data);

        return new SuccessfulResponse($data);
    }
}