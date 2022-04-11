<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\Comment\Comment;
use App\Entities\Comment\CommentInterface;
use App\Repositories\CommentRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateCommentCommandHandler implements CommandHandlerInterface
{

    public function __construct(
        private CommentRepositoryInterface $commentRepository,
        private Connection                 $connection,
        private LoggerInterface            $logger,
    )
    {
    }

    /**
     * @var CreateEntityCommand $command
     */
    public function handle(CommandInterface $command): CommentInterface
    {
        $this->logger->info('Create Comment command started');
        /**
         * @var Comment $comment
         */
        $comment = $command->getEntity();
        $this->connection->prepare($this->getSQL())->execute(
            [
                ':author_id' => $comment->getAuthor()->getId(),
                ':article_id' => $comment->getArticle()->getId(),
                ':text' => $comment->getText(),
            ]
        );
        $id = $this->connection->lastInsertId();
        $this->logger->info('Comment created');

        return $this->commentRepository->findById($id);
    }

    public function getSQL(): string
    {
        return "INSERT INTO Comment(author_id, article_id, text) 
        VALUES (:author_id, :article_id, :text)";
    }
}