<?php

namespace App\Enums;

enum Argument:string
{
    case USER = 'user';
    case ARTICLE = 'article';
    case COMMENT = 'comment';

    public const ARGUMENTS = [Argument::USER, Argument::ARTICLE, Argument::COMMENT];

}