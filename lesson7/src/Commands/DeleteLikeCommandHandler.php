<?php

namespace App\Commands;

use App\Drivers\Connection;
use Psr\Log\LoggerInterface;

class DeleteLikeCommandHandler implements CommandHandlerInterface
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
        $this->logger->info('Delete Like command started');

        $id = $command->getId();
        $this->connection->prepare($this->getSQL())->execute(
            [
                ':id' => (string)$id
            ]
        );
        $this->logger->info('Like deleted');
    }

    public function getSQL(): string
    {
        return "DELETE FROM Like WHERE id = :id";
    }
}