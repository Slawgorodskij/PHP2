<?php

namespace App\Entities\Like;

class Like implements LikeInterface
{
    public const TABLE_NAME = 'Like';

    private ?int $id = null;

    public function __construct(
        private int $author_id,
        private int $article_id,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function getArticleId(): int
    {
        return $this->article_id;
    }

    public function __toString(): string
    {
        return sprintf(
            "[%d] %s %s",
            $this->getId(),
            $this->getAuthorId(),
            $this->getArticleId(),
        );
    }

    public function getTableName(): string
    {
        return static::TABLE_NAME;
    }
}