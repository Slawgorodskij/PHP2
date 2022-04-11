<?php

namespace App\Entities\Like;

use App\Entities\Article\Article;
use App\Entities\EntityInterface;
use App\Entities\User\User;

interface LikeInterface extends EntityInterface
{
    public function getAuthor(): User;

    public function getArticle(): Article;
}