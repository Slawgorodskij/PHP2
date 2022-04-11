<?php

namespace App\Factories;

use App\Entities\Article\ArticleInterface;

interface ArticleFactoryInterface extends FactoryInterface
{
    public function create(): ArticleInterface;
}