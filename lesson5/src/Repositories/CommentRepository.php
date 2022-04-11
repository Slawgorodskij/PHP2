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
        $statement = $this->connection
            ->prepare("SELECT * FROM Comment WHERE id=:id");
        $statement->execute(
            [
                ':id' => (string)$id
            ]
        );

        return $this->getComment($statement, $id);
    }

    public function getComment(PDOStatement $statement, int $id): Comment
    {
        $result = $statement->fetch(PDO::FETCH_OBJ);
        if ($result === false) {
            throw new CommentNotFoundException(
                printf("Cannot find comment with id: %s", $id)
            );
        }

        return new Comment(
            id: $result->id,
            authorId : $result->authorId,
            articleId : $result->articleId,
            text : $result->text
        );
    }
}
