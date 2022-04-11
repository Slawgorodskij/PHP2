<?php

namespace Repositories;

use App\Commands\CreateArticleCommandHandler;
use App\Commands\CreateEntityCommand;
use App\config\SqlLiteConfig;
use App\Connections\ConnectorInterface;
use App\Drivers\Connection;
use App\Drivers\PdoConnectionDriver;
use App\Entities\Article\Article;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\TitleArticleExistException;
use App\Repositories\ArticleRepository;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class ArticleRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenArticleNotFound(): void
    {
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);

        $repository = new ArticleRepository($this->getConnectionStub());

        $this->expectException(ArticleNotFoundException::class);
        $this->expectExceptionMessage('Article not found');

        $repository->getTitleArticle('Скучная статья');
    }


    /**
     * @throws TitleArticleExistException
     */
    public function testItSavesArticleToDatabase(): void
    {
        $repository = new ArticleRepository($this->getConnectionStub());
        $createArticleCommandHandler = new CreateArticleCommandHandler($repository);

        $this->expectException(TitleArticleExistException::class);
        $this->expectExceptionMessage('Такая статья уже написана и размещена');

        $command = new CreateEntityCommand(
            new Article(
                2,
                'Старая скучная статья',
                'Большой текст, содержащий много, очень много слов'
            )
        );

        $createArticleCommandHandler->handle(
            $command
        );
    }


    private function getConnectionStub(): ConnectorInterface
    {
        return new class implements ConnectorInterface {

            public function getConnection(): Connection
            {
                return PdoConnectionDriver::getInstance(SqlLiteConfig::DSN);
            }
        };
    }
}