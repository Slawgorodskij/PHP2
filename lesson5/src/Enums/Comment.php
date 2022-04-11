<?php

namespace App\Enums;

enum Comment: string
{
    case ID = 'id';
    case AUTHOR_ID = 'author_id';
    case ARTICLE_ID = 'article_id';
    case TEXT = 'text';

    public static function getRequiredFields(): array
    {
        return [
            Comment::AUTHOR_ID->value,
            Comment::ARTICLE_ID->value,
            Comment::TEXT->value
        ];
    }
}