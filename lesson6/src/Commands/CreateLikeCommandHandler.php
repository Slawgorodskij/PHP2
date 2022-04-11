<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\Like\Like;
use Psr\Log\LoggerInterface;

class CreateLikeCommandHandler implements CommandHandlerInterface
{

    public function __construct(
        private Connection $connection,
        private LoggerInterface $logger,
    )
    {
    }

    /**
     * @var CreateEntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $this->logger->info('Create Like command started');
        /**
         * @var Like $like
         */
        $like = $command->getEntity();
        $this->connection->prepare($this->getSQL())->execute(
            [
                ':author_id' => $like->getAuthorId(),
                ':article_id' => $like->getArticleId(),
            ]
        );
        $this->logger->info('Like created');
    }

    public function getSQL(): string
    {
        return "INSERT INTO Like(author_id, article_id) 
        VALUES (:author_id, :article_id)";
    }
}