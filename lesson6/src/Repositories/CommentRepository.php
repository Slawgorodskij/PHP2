<?php

namespace App\Repositories;

use App\Entities\Comment\Comment;
use App\Exceptions\CommentNotFoundException;
use PDOStatement;
use PDO;

class CommentRepository extends EntityRepository implements CommentRepositoryInterface
{
    /**
     * @throws CommentNotFoundException
     */
    public function get(int $id): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM Comment WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        return $this->getComment($statement);
    }

    /**
     * @throws CommentNotFoundException
     */
    private function getComment(PDOStatement $statement): Comment
    {
        $commentData = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$commentData) {
            throw new CommentNotFoundException('Comment not found');
        }

        return new Comment(
            $commentData->author,
            $commentData->article,
            $commentData->text
        );
    }
}
