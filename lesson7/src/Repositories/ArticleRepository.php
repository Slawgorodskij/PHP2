<?php

namespace App\Repositories;

use App\Drivers\Connection;
use App\Entities\Article\Article;
use App\Exceptions\ArticleNotFoundException;
use PDO;
use PDOStatement;

class ArticleRepository extends EntityRepository implements ArticleRepositoryInterface
{
    public function __construct(
        Connection                      $connection,
        private UserRepositoryInterface $userRepository
    )
    {
        $this->connection = $connection;
        parent::__construct($connection);
    }

    /**
     * @throws ArticleNotFoundException
     */
    public function findById(int $id): Article
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM Article WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        return $this->getArticle($statement);
    }

    /**
     * @throws ArticleNotFoundException
     */
    public function getTitleArticle(string $title): Article
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM Article WHERE title = :title'
        );
        $statement->execute([
            ':title' => $title,
        ]);

        return $this->getArticle($statement);
    }

    /**
     * @throws ArticleNotFoundException
     */
    public function getArticle(PDOStatement $statement): Article
    {
        $articleData = $statement->fetch(PDO::FETCH_OBJ);

        if (!$articleData) {
            throw new ArticleNotFoundException('Article not found');
        }

        $article = new Article(
            author: $this->userRepository->findById($articleData->author_id),
            title: $articleData->title,
            text: $articleData->text
        );

        $article->setId($articleData->id);
        return $article;
    }
}
