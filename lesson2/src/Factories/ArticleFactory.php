<?php

namespace App\Factories;

use App\Entities\Article\Article;
use App\Entities\Article\ArticleInterface;

final class ArticleFactory extends Factory implements ArticleFactoryInterface
{
    private UserFactoryInterface $userFactory;

    public function __construct(
        UserFactoryInterface $userFactory
    )
    {
        $this->userFactory = $userFactory;
        parent::__construct();
    }

    public function create() : ArticleInterface
    {
        return new Article(
            $this->faker->randomDigitNot(0),
            $this->userFactory->create(),
            $this->faker->title(),
            $this->faker->text(),
        );
    }
}