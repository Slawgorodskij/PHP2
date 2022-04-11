<?php

namespace App\Commands\SymfonyCommands\Articles;

use App\Commands\CreateArticleCommandHandler;
use App\Commands\EntityCommand;
use App\Entities\Article\Article;
use App\Repositories\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateArticle extends Command
{
    public function __construct(
        private CreateArticleCommandHandler $createArticleCommandHandler,
        private UserRepositoryInterface     $userRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('article:create')
            ->setDescription('Creates new article')
            ->addArgument('author_id', InputArgument::REQUIRED, 'Author')
            ->addArgument('title', InputArgument::REQUIRED, 'Title')
            ->addArgument('text', InputArgument::REQUIRED, 'Comment');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Create article command started');

        $author = $this->userRepository->findById((int)$input->getArgument('author_id'));

        $article = new Article(
            $author,
            $input->getArgument('title'),
            $input->getArgument('text'),
        );

        $this->createArticleCommandHandler->handle(new EntityCommand($article));

        $output->writeln('Article created');
        return Command::SUCCESS;
    }

}