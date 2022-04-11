<?php

namespace App\Repositories;

use App\Entities\Comment\Comment;
use PDOStatement;

interface CommentRepositoryInterface
{
    public function get(int $id): Comment;
    public function getComment(PDOStatement $statement, int $id): Comment;
}
