<?php

namespace App\Entities\Comment;

use App\Entities\Article\Article;
use App\Entities\User\User;

class Comment implements CommentInterface
{
    public const TABLE_NAME = 'Comment';

    public function __construct(
        private int $id,
        private int $authorId,
        private int $articleId,
        private string $text,
    ) {}


    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getArticleId(): int
    {
        return $this->articleId;
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
            $this->getAuthorId(),
            $this->getArticleId(),
            $this->getText(),
        );
    }

    public function getTableName(): string
    {
        return static::TABLE_NAME;
    }
}