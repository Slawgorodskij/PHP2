<?php

namespace App\Entities\Like;

use App\Entities\EntityInterface;

interface LikeInterface extends EntityInterface
{
    public function getAuthorId(): int;
    public function getArticleId(): int;
}