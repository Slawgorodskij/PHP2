<?php

namespace App\Stabs;

use App\Entities\Article\Article;
use App\Entities\EntityInterface;
use App\Repositories\ArticleRepositoryInterface;

class DummyArticleRepository implements ArticleRepositoryInterface
{

    public function getTitleArticle(string $title): Article
    {
        return new Article(
             '1',
            'Старая скучная статья',
            'Большой текст, содержащий много, очень много слов',
        );
    }

    public function get(int $id): EntityInterface
    {
        // TODO: Implement get() method.
    }
}