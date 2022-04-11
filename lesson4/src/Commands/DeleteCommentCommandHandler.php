<?php

namespace App\Commands;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector;

class DeleteCommentCommandHandler implements CommandHandlerInterface
{
    private \PDOStatement|false $stmt;

    public function __construct(private ?ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqlLiteConnector();
        $this->stmt = $this->connector->getConnection()->prepare($this->getSQL());
    }

    public function handle($id): void
    {
        $this->stmt->execute(
            [
                ':id' => (string)$id
            ]
        );
    }


    public function getSQL(): string
    {
        return "DELETE FROM Comment WHERE id = :id";
    }
}
