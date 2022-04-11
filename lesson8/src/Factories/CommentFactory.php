<?php

namespace App\Factories;

use App\Decorator\CommentDecorator;
use App\Entities\Comment\Comment;
use App\Entities\Comment\CommentInterface;
use JetBrains\PhpStorm\Pure;

final class CommentFactory implements CommentFactoryInterface
{
    #[Pure] public function create(CommentDecorator $commentDecorator): CommentInterface
    {
        return new Comment(
            $commentDecorator->author_id,
            $commentDecorator->article_id,
            $commentDecorator->text,
        );
    }
}