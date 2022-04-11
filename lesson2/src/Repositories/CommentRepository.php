<?php

namespace App\Repositories;

use App\Entities\EntityInterface;
use App\Entities\Comment\Comment;
use App\Exceptions\CommentNotFoundException;
use App\Factories\EntityRepository;
use PDO;
use PDOStatement;

class CommentRepository extends EntityRepository implements CommentRepositoryInterface
{
    public function save(EntityInterface $entity): void
    {
        /**
         * @var Comment $entity
         */
        $statement = $this->connector->getConnection()
            ->prepare("INSERT INTO comments (author_id, article_id, comment) VALUES (:user_id, :article_id, :text)");

        $statement->execute(
            [
                ':user_id' => $entity->getAuthor()->getId(),
                ':article_id' => $entity->getArticle()->getId(),
                ':text' => $entity->getText(),
            ]
        );
    }

    public function get(int $id): EntityInterface
    {
        $statement = $this->connector->getConnection()->prepare(
            'SELECT * FROM comments WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        return $this->getComment($statement, $comment);
    }

    /**
     * @throws CommentNotFoundException
     */
    private function getComment(PDOStatement $statement, Comment $comment): Comment
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            throw new CommentNotFoundException(
                sprintf("Cannot find comment: %s", $comment->getText()));
        }

        return $comment;
    }
}