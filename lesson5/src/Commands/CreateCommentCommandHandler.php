<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\Comment\Comment;

class CreateCommentCommandHandler implements CommandHandlerInterface
{
    private \PDOStatement|false $stmt;

    public function __construct(
        private Connection $connection
    )
    {
        $this->stmt = $connection->prepare($this->getSQL());
    }

    /**
     * @var CreateEntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        /**
         * @var Comment $comment
         */
        $comment = $command->getEntity();
        $this->stmt->execute(
            [
                ':author_id' => $comment->getAuthorId(),
                ':article_id' => $comment->getArticleId(),
                ':text' => $comment->getText(),
            ]
        );
    }

    public function getSQL(): string
    {
        return "INSERT INTO Comment(author_id, article_id, text) 
        VALUES (:author_id, :article_id, :text)";
    }
}
