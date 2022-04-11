<?php

namespace App\Repositories;

use App\Entities\Article\Article;

interface ArticleRepositoryInterface
{
    public function findById(int $id): Article;

    public function getTitleArticle(string $title): Article;
}
