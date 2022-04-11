<?php

namespace App\Http\Actions\Article;

use App\Exceptions\HttpException;
use App\Exceptions\TitleArticleExistException;
use App\Http\Actions\ActionInterface;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleRepositoryInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;

class FindByArticle implements ActionInterface
{
    public function __construct(private ArticleRepositoryInterface $articlesRepository)
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $title = $request->query('title');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $article = $this->articlesRepository->getTitleArticle($title);
        } catch (TitleArticleExistException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'title' => $article->getTitle(),
            'text' => $article->getText(),
        ]);
    }
}