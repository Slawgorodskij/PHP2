<?php

namespace App\Http\AuthArticles;

use App\Entities\Article\Article;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\AuthException;
use App\Exceptions\HttpException;
use App\Exceptions\InvalidArgumentException;
use App\Http\Request;
use App\Repositories\ArticleRepositoryInterface;

class JsonBodyArticleIdIdentification implements IdentificationArticleInterface
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
    )
    {
    }

    public function getArticle(Request $request): Article
    {
        try {
            $articleId = $request->jsonBodyField('article_id');
        } catch (HttpException|InvalidArgumentException $e) {
            throw new AuthException($e->getMessage());
        }
        try {
            return $this->articleRepository->findById($articleId);
        } catch (ArticleNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }
    }
}