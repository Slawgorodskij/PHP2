<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\Like\Like;

class CreateLikeCommandHandler implements CommandHandlerInterface
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
         * @var Like $like
         */
        $like = $command->getEntity();
        $this->stmt->execute(
            [
                ':author_id' => $like->getAuthorId(),
                ':article_id' => $like->getArticleId(),
            ]
        );
    }

    public function getSQL(): string
    {
        return "INSERT INTO Like(author_id, article_id) 
        VALUES (:author_id, :article_id)";
    }
}