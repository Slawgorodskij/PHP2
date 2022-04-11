<?php

namespace App\Commands;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector;

class DeleteArticleCommandHandler implements CommandHandlerInterface
{
    private \PDOStatement|false $stmt;

    public function __construct(private ?ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqlLiteConnector();
        $this->stmt = $this->connector->getConnection()->prepare($this->getSQL());
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
