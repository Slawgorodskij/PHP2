<?php

namespace App\Entities\Article;


use App\Entities\User\User;
use App\Traits\Author;
use App\Traits\Id;
use App\Traits\Text;
use App\Traits\Title;

class Article implements ArticleInterface
{
    public const TABLE_NAME = 'Article';

    use Id;
    use Author;
    use Title;
    use Text;

    public function __construct(
        User   $author,
        string $title,
        string $text,
    )
    {
        $this->author = $author;
        $this->title = $title;
        $this->text = $text;
    }


    public function __toString(): string
    {
        return sprintf(
            "[%d] %s %s %s",
            $this->getId(),
            $this->getAuthor()->getId(),
            $this->getTitle(),
            $this->getText(),
        );
    }

    public function getTableName(): string
    {
        return static::TABLE_NAME;
    }
}