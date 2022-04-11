<?php

namespace App\Repositories;

use App\Entities\Article\Article;
use App\Entities\EntityInterface;
use App\Exceptions\ArticleNotFoundException;
use App\Factories\EntityRepository;
use PDO;
use PDOStatement;

class ArticleRepository extends EntityRepository implements ArticleRepositoryInterface
{

    public function save(EntityInterface $entity): void
    {
        /**
         * @var Article $entity
         */
        $statement = $this->connector->getConnection()
            ->prepare("INSERT INTO articles (user_id, title, text) VALUES (:user_id, :title, :text)");

        $statement->execute(
            [
                ':user_id' => $entity->getAuthor()->getId(),
                ':title' => $entity->getTitle(),
                ':text' => $entity->getText(),
            ]
        );
    }

    public function get(int $id): EntityInterface
    {
        $statement = $this->connector->getConnection()->prepare(
            'SELECT * FROM articles WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        return $this->getArticle($statement, $article);
    }

    /**
     * @throws ArticleNotFoundException
     */
    private function getArticle(PDOStatement $statement, Article $article): Article
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            throw new ArticleNotFoundException(
                sprintf("Cannot find article: %s", $article->getTitle()));
        }

        return $article;
    }
}