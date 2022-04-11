<?php

namespace App\Entities\Like;

use App\Entities\Article\Article;
use App\Entities\User\User;
use App\Traits\Articles;
use App\Traits\Author;
use App\Traits\Id;

class Like implements LikeInterface
{
    public const TABLE_NAME = 'Like';

    use Id;
    use Author;
    use Articles;

    public function __construct(
        User    $author,
        Article $article,
    )
    {
        $this->author = $author;
        $this->article = $article;
    }

    public function __toString(): string
    {
        return sprintf(
            "[%d] %s %s",
            $this->getId(),
            $this->getAuthor()->getId(),
            $this->getArticle()->getId(),
        );
    }

    public function getTableName(): string
    {
        return static::TABLE_NAME;
    }
}