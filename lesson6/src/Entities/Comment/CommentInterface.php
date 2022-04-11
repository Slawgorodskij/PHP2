<?php

namespace App\Entities\Comment;

use App\Entities\Article\Article;
use App\Entities\EntityInterface;
use App\Entities\User\User;

interface CommentInterface extends EntityInterface
{
    public function getAuthorId(): int;
    public function getArticleId(): int;
    public function getText(): string;
}