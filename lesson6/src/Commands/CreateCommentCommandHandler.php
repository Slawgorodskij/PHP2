<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\Comment\Comment;
use Psr\Log\LoggerInterface;

class CreateCommentCommandHandler implements CommandHandlerInterface
{

    public function __construct(
        private Connection      $connection,
        private LoggerInterface $logger,
    )
    {
    }

    /**
     * @var CreateEntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $this->logger->info('Create Comment command started');
        /**
         * @var Comment $comment
         */
        $comment = $command->getEntity();
        $this->connection->prepare($this->getSQL())->execute(
            [
                ':author_id' => $comment->getAuthorId(),
                ':article_id' => $comment->getArticleId(),
                ':text' => $comment->getText(),
            ]
        );
        $this->logger->info('Comment created');
    }

    public function getSQL(): string
    {
        return "INSERT INTO Comment(author_id, article_id, text) 
        VALUES (:author_id, :article_id, :text)";
    }
}
