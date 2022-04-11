<?php

namespace App\Entities\Comment;

use App\Entities\Article\Article;
use App\Entities\User\User;
use App\Traits\Articles;
use App\Traits\Author;
use App\Traits\Id;
use App\Traits\Text;

class Comment implements CommentInterface
{
    public const TABLE_NAME = 'Comment';

    use Id;
    use Author;
    use Articles;
    use Text;

    public function __construct(
        User    $author,
        Article $article,
        string  $text,
    )
    {
        $this->author = $author;
        $this->article = $article;
        $this->text = $text;
    }

    public function __toString(): string
    {
        return sprintf(
            "[%d] %s %s %s",
            $this->getId(),
            $this->getAuthor()->getId(),
            $this->getArticle()->getId(),
            $this->getText(),
        );
    }

    public function getTableName(): string
    {
        return static::TABLE_NAME;
    }
}