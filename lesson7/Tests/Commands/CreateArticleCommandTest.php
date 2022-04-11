<?php

namespace Commands;

use App\Commands\CreateArticleCommandHandler;
use App\Commands\CreateEntityCommand;
use App\Entities\Article\Article;
use App\Entities\EntityInterface;
use App\Exceptions\ArticleNotFoundException;
use App\Exceptions\TitleArticleExistException;
use App\Repositories\ArticleRepositoryInterface;
use App\Stabs\DummyArticleRepository;
use PHPUnit\Framework\TestCase;
use Tests\Traits\LoggerTrait;


class CreateArticleCommandTest extends TestCase
{
    use LoggerTrait;

    public function testItThrowsAnExceptionWhenArticleExists(): void
    {
        $createUserCommandHandler = new CreateArticleCommandHandler(new DummyArticleRepository(), $this->getLogger());

        $this->expectException(TitleArticleExistException::class);
        $this->expectExceptionMessage('Такая статья уже написана и размещена');

        $command = new CreateEntityCommand(
            new Article(
                '2',
                'Старая скучная статья',
                'Большой текст, содержащий много, очень много слов'
            )
        );

        $createUserCommandHandler->handle($command);
    }

    public function testItSavesArticleToRepository(): void
    {
        $articlesRepository = new class implements ArticleRepositoryInterface {

            private bool $called = false;

            public function get(int $id): EntityInterface
            {
                throw new ArticleNotFoundException("Not found");
            }

            public function getTitleArticle(string $title): Article
            {
                $this->called = true;
                return new Article('1', 'Заголовок', 'Какой-то текст');
            }

            public function wasCalled(): bool
            {
                return $this->called;
            }
        };

        $createArticleCommandHandler = new CreateArticleCommandHandler($articlesRepository, $this->getLogger());

        $this->expectException(TitleArticleExistException::class);
        $this->expectExceptionMessage('Такая статья уже написана и размещена');

        $command = new CreateEntityCommand(
            new Article(
                3,
                'Новая веселая статья',
                'Большой текст, содержащий много, очень много слов'
            )
        );

        $createArticleCommandHandler->handle($command);
        $this->assertTrue($articlesRepository->wasCalled());
    }

}