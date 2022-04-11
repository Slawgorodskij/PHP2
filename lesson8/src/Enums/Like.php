<?php

namespace App\Enums;

enum Like: string
{
    case ID = 'id';
    case AUTHOR_ID = 'author_id';
    case ARTICLE_ID = 'article_id';

    public static function getRequiredFields(): array
    {
        return [
            Like::AUTHOR_ID->value,
            Like::ARTICLE_ID->value,
        ];
    }
}