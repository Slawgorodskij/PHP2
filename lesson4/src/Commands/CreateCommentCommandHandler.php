<?php

namespace App\Commands;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector;

class CreateCommentCommandHandler implements CommandHandlerInterface
{
    private \PDOStatement|false $stmt;

    public function __construct(private ?ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqlLiteConnector();
        $this->stmt = $this->connector->getConnection()->prepare($this->getSQL());
    }

    /**
     * @var CreateEntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $comment = $command->getEntity();
        $this->stmt->execute(
            [
                ':author_id' => $comment->getAuthor(),
                ':article_id' => $comment->getArticle(),
                ':text' => $comment->getText(),
            ]
        );
    }

    public function getSQL(): string
    {
        return "INSERT INTO Comment (author_id, article_id, text) 
        VALUES (:author_id, :article_id, :text)";
    }
}
