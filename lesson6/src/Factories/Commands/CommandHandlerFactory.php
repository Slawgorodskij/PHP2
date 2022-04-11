<?php

namespace App\Factories\Commands;

use App\Commands\CommandHandlerInterface;
use App\Commands\CreateArticleCommandHandler;
use App\Commands\CreateCommentCommandHandler;
use App\Commands\CreateUserCommandHandler;
use App\Entities\Article\Article;
use App\Entities\Comment\Comment;
use App\Entities\User\User;

class CommandHandlerFactory implements CommandHandlerFactoryInterface
{
    public function create(string $entityType): CommandHandlerInterface
    {
        return match ($entityType) {
            User::class => new CreateUserCommandHandler(),
            Article::class => new CreateArticleCommandHandler(),
            Comment::class => new CreateCommentCommandHandler()
        };
    }
}