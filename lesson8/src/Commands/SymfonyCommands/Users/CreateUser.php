<?php

namespace App\Commands\SymfonyCommands\Users;


use App\Commands\CreateUserCommandHandler;
use App\Commands\EntityCommand;
use App\Entities\User\User;
use App\Exceptions\UserEmailExistException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUser extends Command
{
    public function __construct(
        private UserRepositoryInterface  $userRepository,
        private CreateUserCommandHandler $createUserCommandHandler
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('user:create')
            ->setDescription('Creates new user')
            ->addArgument('firstName', InputArgument::REQUIRED, 'First name')
            ->addArgument('lastName', InputArgument::REQUIRED, 'Last name')
            ->addArgument('email', InputArgument::REQUIRED, 'email')
            ->addArgument('password', InputArgument::REQUIRED, 'Password');
    }

    /**
     * @throws UserEmailExistException
     */
    protected function execute(
        InputInterface  $input,
        OutputInterface $output,
    ): int
    {
        $output->writeln('Create user command started');
        $email = $input->getArgument('email');

        if ($this->userExists($email)) {
            $output->writeln("User already exists: $email");
            return Command::FAILURE;
        }

        $user = new User(
            $email,
            $input->getArgument('firstName'),
            $input->getArgument('lastName'),
            $input->getArgument('password'),
        );

        $createdUser = $this->createUserCommandHandler->handle(new EntityCommand($user));

        $output->writeln('User created: ' . $createdUser->getId());
        return Command::SUCCESS;
    }

    private function userExists(string $email): bool
    {
        try {
            $this->userRepository->getUserByEmail($email);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }

}