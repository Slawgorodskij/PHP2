<?php

namespace App\Repositories;

use App\Entities\Comment\Comment;

interface CommentRepositoryInterface
{
    public function findById(int $id): Comment;
}
