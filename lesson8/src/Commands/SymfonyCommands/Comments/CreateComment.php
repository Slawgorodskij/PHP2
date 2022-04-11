<?php

namespace App\Commands\SymfonyCommands\Comments;

use App\Commands\CreateCommentCommandHandler;
use App\Commands\EntityCommand;
use App\Entities\Comment\Comment;
use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateComment extends Command
{
    public function __construct(
        private CreateCommentCommandHandler $createCommentCommandHandler,
        private UserRepositoryInterface     $userRepository,
        private ArticleRepositoryInterface  $articleRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('comment:create')
            ->setDescription('Creates new comment')
            ->addArgument('author_id', InputArgument::REQUIRED, 'Author')
            ->addArgument('article_id', InputArgument::REQUIRED, 'Article')
            ->addArgument('text', InputArgument::REQUIRED, 'Comment');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Create comment command started');

        $author = $this->userRepository->findById((int)$input->getArgument('author_id'));
        $article = $this->articleRepository->findById((int)$input->getArgument('article_id'));

        $comment = new Comment(
            $author,
            $article,
            $input->getArgument('text'),
        );

        $this->createCommentCommandHandler->handle(new EntityCommand($comment));

        $output->writeln('Comment created');
        return Command::SUCCESS;
    }
}