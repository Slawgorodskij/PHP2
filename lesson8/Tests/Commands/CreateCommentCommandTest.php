<?php

namespace Commands;

use App\Commands\CreateCommentCommandHandler;
use App\Commands\CreateEntityCommand;
use App\Entities\Comment\Comment;
use App\Exceptions\CommentNotFoundException;
use App\Repositories\CommentRepositoryInterface;
use App\Entities\EntityInterface;
use PHPUnit\Framework\TestCase;
use Tests\Traits\LoggerTrait;

class CreateCommentCommandTest extends TestCase
{
    use LoggerTrait;

    public function testItSavesCommentToRepository(): void
    {
        $commentsRepository = new class implements CommentRepositoryInterface {

            private bool $called = false;

            public function get(int $id): EntityInterface
            {
                throw new CommentNotFoundException("Comment not found");
            }

            public function wasCalled(): bool
            {
                return $this->called = true;
            }
        };

        $createCommentCommandHandler = new CreateCommentCommandHandler($this->getLogger());

        $command = new CreateEntityCommand(
            new Comment(
                2,
                2,
                'Текст, содержащий много, очень много слов'
            )
        );

        $createCommentCommandHandler->handle($command);
        $this->assertTrue($commentsRepository->wasCalled());
    }
}