<?php

namespace App\Models;

class News
{
    public function __construct(
        protected int    $id,
        protected int    $userId,
        protected string $newsTitle,
        protected string $newsBody,
    )
    {
    }

    public function __toString()
    {
        return $this->newsTitle . ' ' . $this->newsBody;
    }
}