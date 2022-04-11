<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\Article\Article;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\TitleArticleExistException;
use App\Repositories\ArticleRepositoryInterface;

class CreateArticleCommandHandler implements CommandHandlerInterface
{
    private \PDOStatement|false $stmt;

    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private Connection                 $connection,
    )
    {
        $this->stmt = $connection->prepare($this->getSQL());
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
                    ':author_id' => $article->getAuthorId(),
                    ':header' => $article->getTitle(),
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
        VALUES (:author_id, :header, :text)";
    }
}
