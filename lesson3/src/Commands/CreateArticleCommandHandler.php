<?php

namespace App\Commands;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector;
use App\Entities\Article\Article;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\TitleArticleExistException;
use App\Repositories\ArticleRepositoryInterface;

class CreateArticleCommandHandler implements CommandHandlerInterface
{
    private \PDOStatement|false $stmt;

    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private ?ConnectorInterface        $connector = null)
    {
        $this->connector = $connector ?? new SqlLiteConnector();
        $this->stmt = $this->connector->getConnection()->prepare($this->getSQL());
    }

    /**
     * @throws TitleArticleExistException
     * @var CreateEntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        /**
         * @var Article $article
         */
        $article = $command->getEntity();
        $title = $article->getTitle();

        if (!$this->isArticleExists($title)) {
            $this->stmt->execute(
                [
                    ':author_id' => $article->getAuthor(),
                    ':title' => $article->getTitle(),
                    ':text' => $article->getText(),
                ]
            );
        } else {
            throw new TitleArticleExistException();
        }
    }

    private function isArticleExists(string $title): bool
    {
        try {
            $this->articleRepository->getTitleArticle($title);
        } catch (ArticleNotFoundException) {
            return false;
        }
        return true;
    }

    public function getSQL(): string
    {
        return "INSERT INTO Article (author_id, title, text) 
        VALUES (:author_id, :title, :text)";
    }
}
