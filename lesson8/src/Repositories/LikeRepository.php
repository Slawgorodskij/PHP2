<?php

namespace App\Repositories;

use App\Drivers\Connection;
use App\Entities\Like\Like;
use App\Exceptions\CommentNotFoundException;
use PDO;
use PDOStatement;

class LikeRepository extends EntityRepository implements LikeRepositoryInterface
{
    public function __construct(
        Connection                         $connection,
        private UserRepositoryInterface    $userRepository,
        private ArticleRepositoryInterface $articleRepository,
    )
    {
        $this->connection = $connection;
        parent::__construct($connection);
    }

    /**
     * @throws CommentNotFoundException
     */
    public function findById(int $id): Like
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
        $likeData = $statement->fetch(PDO::FETCH_OBJ);
        if (!$likeData) {
            throw new CommentNotFoundException(
                printf("Cannot find like with id: %s", $id)
            );
        }
        $like = new Like(
            $this->userRepository->findById($likeData->author_id),
            $this->articleRepository->findById($likeData->article_id)
        );

        $like->setId($likeData->id);
        return $like;
    }
}