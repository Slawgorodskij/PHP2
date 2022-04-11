<?php

namespace App\Http\Actions\Article;

use App\Commands\DeleteArticleCommandHandler;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\HttpException;
use App\Http\Actions\User\ActionInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Repositories\ArticleRepositoryInterface;

class DeleteArticle implements ActionInterface
{
    public function __construct(
        private DeleteArticleCommandHandler $deleteArticleCommandHandler,
        private ArticleRepositoryInterface  $articlesRepository
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $article_id = $request->query('id');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $this->articlesRepository->get($article_id);
        } catch (ArticleNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $this->deleteArticleCommandHandler->handle($article_id);
        } catch (ArticleNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        return new SuccessfulResponse([
            'article' => 'Статья удалена',
        ]);
    }
}