<?php

namespace App\Traits;


use App\Entities\Article\Article;

trait Articles
{
    private Article $article;

    public function getArticle(): Article
    {
        return $this->article;
    }

    public function setArticle(Article $article): self
    {
        $this->article = $article;

        return $this;
    }

}