<?php

namespace App\Commands;

use App\Drivers\Connection;
use Psr\Log\LoggerInterface;

class DeleteCommentCommandHandler implements CommandHandlerInterface
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
        $this->logger->info('Delete Comment command started');
        /**
         * @var EntityCommand $command
         */
        $comment = $command->getEntity();
        $this->connection->prepare($this->getSQL())->execute(
            [
                ':id' => $comment->getId(),
            ]
        );
        $this->logger->info('Comment deleted');
    }


    public function getSQL(): string
    {
        return "DELETE FROM Comment WHERE id = :id";
    }
}
