<?php

namespace App\Http\AuthArticles;

use App\Entities\Article\Article;
use App\Http\Request;

interface IdentificationArticleInterface
{
    public function getArticle(Request $request): Article;
}