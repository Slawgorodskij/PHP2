<?php

namespace App\Repositories;

use App\Entities\Article\Article;
use App\Exceptions\ArticleNotFoundException;
use PDO;
use PDOStatement;

class ArticleRepository extends EntityRepository implements ArticleRepositoryInterface
{
    /**
     * @throws \Exception
     */
    public function get(int $id): Article
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM Article WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        return $this->getArticle($statement, $id);
    }

    public function getArticle(PDOStatement $statement, int $id): Article
    {
        $result = $statement->fetch(PDO::FETCH_OBJ);
        if ($result === false) {
            throw new ArticleNotFoundException(
                sprintf("Cannot find article with id: %s", $id)
            );
        }

        return new Article(
            id: $result->id,
            authorId : $result->authorId,
            title : $result->title,
            text : $result->text
        );
    }
}
