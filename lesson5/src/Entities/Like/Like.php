<?php

namespace App\Entities\Like;

class Like implements LikeInterface
{
    public const TABLE_NAME = 'Like';

    public function __construct(
        private int $id,
        private int $authorId,
        private int $articleId,
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