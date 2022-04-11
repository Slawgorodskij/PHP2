<?php

use App\Commands\SymfonyCommands\Articles\CreateArticle;
use App\Commands\SymfonyCommands\Articles\DeleteArticle;
use App\Commands\SymfonyCommands\Comments\CreateComment;
use App\Commands\SymfonyCommands\Comments\DeleteComment;
use App\Commands\SymfonyCommands\PopulateDB;
use App\Commands\SymfonyCommands\Users\CreateUser;
use Symfony\Component\Console\Application;

$container = require __DIR__ . '/bootstrap.php';

$application = new Application();

$commandsClasses = [
    CreateUser::class,
    DeleteArticle::class,
    PopulateDB::class,
    CreateComment::class,
    CreateArticle::class,
    DeleteComment::class,
];

foreach ($commandsClasses as $commandClass) {
    $command = $container->get($commandClass);
    $application->add($command);
}

$application->run();
