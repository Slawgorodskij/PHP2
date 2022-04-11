<?php

namespace App\Commands;

use App\Drivers\Connection;
use Psr\Log\LoggerInterface;

class DeleteArticleCommandHandler implements CommandHandlerInterface
{

    public function __construct(
        private Connection      $connection,
        private LoggerInterface $logger,
    )
    {
    }

    /**
     * @var DeleteEntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $this->logger->info('Delete Article command started');
        /**
         * @var EntityCommand $command
         */
        $article = $command->getEntity();
        $this->connection->prepare($this->getSQL())->execute(
            [
                ':id' => $article->getId(),
            ]
        );
        $this->logger->info('Article deleted');
    }

    public function getSQL(): string
    {
        return "DELETE FROM Article WHERE id = :id";
    }
}
