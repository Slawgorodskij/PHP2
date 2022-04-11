<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\Article\Article;
use App\Entities\Article\ArticleInterface;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\TitleArticleExistException;
use App\Repositories\ArticleRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateArticleCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private Connection                 $connection,
        private LoggerInterface            $logger,
    )
    {
    }

    /**
     * @throws TitleArticleExistException
     * @var CreateEntityCommand $command
     */
    public function handle(CommandInterface $command): ArticleInterface
    {
        $this->logger->info('Create Article command started');
        /**
         * @var Article $article
         */
        $article = $command->getEntity();
        $title = $article->getTitle();

        if (!$this->isArticleExists($title)) {
            try {
                $this->connection->beginTransaction();
                $this->connection->prepare($this->getSQL())->execute(
                    [
                        ':author_id' => $article->getAuthor()->getId(),
                        ':title' => $article->getTitle(),
                        ':text' => $article->getText(),
                    ]
                );
                $id = $this->connection->lastInsertId();
                $this->connection->commit();
                $this->logger->info("Article created title: $title");

            } catch (\PDOException $e) {
                $this->connection->rollback();
                print "Error!: " . $e->getMessage() . "</br>";
            }
        } else {
            $this->logger->warning("Article already exists: $title");
            throw new TitleArticleExistException();
        }

        return $this->articleRepository->findById($id);
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
