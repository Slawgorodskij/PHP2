<?php

namespace App\Http\Actions\Article;

use App\Commands\CreateArticleCommandHandler;
use App\Commands\CreateEntityCommand;
use App\Entities\Article\Article;
use App\Exceptions\HttpException;
use App\Exceptions\TitleArticleExistException;
use App\Http\Actions\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;

class CreateArticle implements ActionInterface
{

    public function __construct(
        private CreateArticleCommandHandler $createArticleCommandHandler)
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $article = new Article(
                $request->jsonBodyField('author_id'),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
            $this->createArticleCommandHandler->handle(new CreateEntityCommand($article));
        } catch (HttpException|TitleArticleExistException $exception) {
            return new ErrorResponse($exception->getMessage());
        }
        return new SuccessfulResponse([
            'article' => $article->getTitle(),
        ]);
    }
}
