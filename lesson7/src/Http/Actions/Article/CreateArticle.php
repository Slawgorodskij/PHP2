<?php

namespace App\Http\Actions\Article;

use App\Commands\CreateArticleCommandHandler;
use App\Commands\EntityCommand;
use App\Entities\Article\Article;
use App\Exceptions\HttpException;
use App\Exceptions\TitleArticleExistException;
use App\Http\Actions\User\ActionInterface;
use App\Http\Auth\AuthenticationInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use Psr\Log\LoggerInterface;

class CreateArticle implements ActionInterface
{

    public function __construct(
        private CreateArticleCommandHandler $createArticleCommandHandler,
        private AuthenticationInterface     $authentication,
        private LoggerInterface             $logger,
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $article = new Article(
                $this->authentication->getUser($request),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
            $article = $this->createArticleCommandHandler->handle(new EntityCommand($article));
        } catch (HttpException|TitleArticleExistException $e) {
            $this->logger->error($e);
            return new ErrorResponse($e->getMessage());
        }

        $data = [
            'id' => $article->getId(),
            'author_id' => $article->getAuthor()->getId(),
            'title' => $article->getTitle(),
            'text' => $article->getText(),
        ];

        $this->logger->info('Create new Article', $data);

        return new SuccessfulResponse($data);
    }
}
