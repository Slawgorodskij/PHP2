<?php

namespace App\Entities\Article;


class Article implements ArticleInterface
{
    public const TABLE_NAME = 'Article';

    private ?int $id = null;

    public function __construct(
        private int $author_id,
        private string $title,
        private string $text,
    ) {}


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function getTitle(): string
    {
        return $this->title;
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
            $this->getTitle(),
            $this->getText(),
        );
    }

    public function getTableName(): string
    {
        return static::TABLE_NAME;
    }
}