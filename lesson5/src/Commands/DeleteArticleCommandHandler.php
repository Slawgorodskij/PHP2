<?php

namespace App\Commands;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector;
use App\Drivers\Connection;

class DeleteArticleCommandHandler implements CommandHandlerInterface
{
    private \PDOStatement|false $stmt;

    public function __construct(
        private Connection $connection
    )
    {
        $this->stmt = $connection->prepare($this->getSQL());
    }

    /**
     * @var DeleteEntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $id = $command->getId();
        $this->stmt->execute(
            [
                ':id' => (string)$id
            ]
        );
    }


    public function getSQL(): string
    {
        return "DELETE FROM Article WHERE id = :id";
    }
}
