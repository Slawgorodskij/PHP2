<?php

namespace App\Repositories;

use App\Entities\Article\Article;

interface ArticleRepositoryInterface extends EntityRepositoryInterface
{
    public function getTitleArticle(string $title): Article;
}