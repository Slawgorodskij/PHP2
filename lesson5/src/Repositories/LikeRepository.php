<?php

namespace App\Repositories;

use App\Entities\Like\Like;

use App\Exceptions\CommentNotFoundException;
use PDO;
use PDOStatement;

class LikeRepository extends EntityRepository implements LikeRepositoryInterface
{
    /**
     * @throws CommentNotFoundException
     */
    public function get(int $id): Like
    {
        $statement = $this->connection
            ->prepare("SELECT * FROM Like WHERE id=:id");
        $statement->execute(
            [
                ':id' => (string)$id
            ]
        );

        return $this->getLike($statement, $id);
    }

    public function getLike(PDOStatement $statement, int $id): Like
    {
        $result = $statement->fetch(PDO::FETCH_OBJ);
        if ($result === false) {
            throw new CommentNotFoundException(
                printf("Cannot find like with id: %s", $id)
            );
        }

        return new Like(
            id: $result->id,
            authorId: $result->authorId,
            articleId: $result->articleId,
        );
    }
}