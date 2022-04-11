<?php

namespace App\Repositories;

use App\Entities\Article\Article;
use PDOStatement;

interface ArticleRepositoryInterface
{
    public function getTitleArticle(string $title): Article;

}
