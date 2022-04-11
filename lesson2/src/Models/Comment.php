<?php

namespace App\Models;

class Comment
{
    public function __construct(
        protected int    $id,
        protected int $userId,
        protected int $newsId,
        protected string $commentBody,
    )
    {
    }

    public function __toString()
    {
        return 'the user writes ' . $this->commentBody;
    }
}