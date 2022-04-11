<?php

namespace App\Entities\Comment;

use App\Entities\Article\Article;
use App\Entities\User\User;

class Comment implements CommentInterface
{
    public const TABLE_NAME = 'Comment';

    private ?int $id = null;

    public function __construct(
        private string $author_id,
        private string $article_id,
        private string $text,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthor(): string
    {
        return $this->author_id;
    }

    public function getArticle(): string
    {
        return $this->article_id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function __toString(): string
    {
        return sprintf(
            "[%d] %s %s %s",
            $this->getId(),
            $this->getAuthor(),
            $this->getArticle(),
            $this->getText(),
        );
    }

    public function getTableName(): string
    {
        return static::TABLE_NAME;
    }
}