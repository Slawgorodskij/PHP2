<?php

namespace App\Repositories;

use App\Drivers\Connection;
use App\Entities\Comment\Comment;
use App\Exceptions\CommentNotFoundException;
use PDOStatement;
use PDO;

class CommentRepository extends EntityRepository implements CommentRepositoryInterface
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
    public function findById(int $id): Comment
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
        $commentData = $statement->fetch(PDO::FETCH_OBJ);

        if (!$commentData) {
            throw new CommentNotFoundException('Comment not found');
        }

        $comment = new Comment(
            $this->userRepository->findById($commentData->author_id),
            $this->articleRepository->findById($commentData->article_id),
            $commentData->text,
        );

        $comment->setId($commentData->id);

        return $comment;
    }
}
