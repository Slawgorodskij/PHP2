<?php

namespace App\Factories;

use App\Decorator\LikeDecorator;
use App\Entities\Like\Like;
use App\Entities\Like\LikeInterface;
use JetBrains\PhpStorm\Pure;

final class LikeFactory implements LikeFactoryInterface
{
   #[Pure] public function create(LikeDecorator $likeDecorator): LikeInterface
    {
        return new Like(
            $likeDecorator->author_id,
            $likeDecorator->article_id,
        );
    }
}