<?php

namespace App\Commands\SymfonyCommands;

use App\Commands\CreateArticleCommandHandler;
use App\Commands\CreateCommentCommandHandler;
use App\Commands\CreateUserCommandHandler;
use App\Commands\EntityCommand;
use App\Entities\Article\Article;
use App\Entities\Comment\Comment;
use App\Entities\User\User;
use Faker\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDB extends Command
{
    public function __construct(
        private Generator                   $faker,
        private CreateUserCommandHandler    $createUserCommandHandler,
        private CreateArticleCommandHandler $articleCommandHandler,
        private CreateCommentCommandHandler $commentCommandHandler,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data')
            ->addArgument('numberOfUsers', InputArgument::REQUIRED, 'Number of users')
            ->addArgument('numberOfArticles', InputArgument::REQUIRED, 'Number of articles');
    }

    protected function execute(
        InputInterface  $input,
        OutputInterface $output,
    ): int
    {
        $userNumber = $input->getArgument('numberOfUsers');
        $articleNumber = $input->getArgument('numberOfArticles');

        $users = [];
        for ($i = 0; $i < $userNumber; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->getEmail());
        }

        $articles = [];
        foreach ($users as $user) {
            for ($i = 0; $i < $articleNumber; $i++) {
                $article = $this->createFakeArticle($user);
                $articles[] = $article;
                $output->writeln('Article created: ' . $article->getTitle());
            }
        }

        foreach ($users as $user) {
            foreach ($articles as $article) {
                $this->createFakeComment($user, $article);
                $output->writeln('Comment created');
            }
        }

        return Command::SUCCESS;
    }

    private function createFakeUser(): User
    {
        $user = new User(
            $this->faker->firstName,
            $this->faker->lastName,
            $this->faker->email,
            $this->faker->password,
        );

        return $this->createUserCommandHandler->handle(new EntityCommand($user));
    }

    private function createFakeArticle(User $author): Article
    {
        $article = new Article(
            $author,
            $this->faker->sentence(6, true),
            $this->faker->realText
        );

        return $this->articleCommandHandler->handle(new EntityCommand($article));
    }

    private function createFakeComment(User $author, Article $article): Comment
    {
        $comment = new Comment(
            $author,
            $article,
            $this->faker->realText
        );

        return $this->commentCommandHandler->handle(new EntityCommand($comment));
    }
}