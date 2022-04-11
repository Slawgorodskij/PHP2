<?php

use App\Container\DIContainer;
use App\Drivers\Connection;
use App\Drivers\PdoConnectionDriver;
use App\Http\Auth\AuthenticationInterface;
use App\Http\Auth\IdentificationInterface;
use App\Http\Auth\JsonBodyUserEmailIdentification;
use App\Http\Auth\JsonBodyUserIdIdentification;
use App\Http\Auth\PasswordAuthentication;
use App\Http\Auth\PasswordAuthenticationInterface;
use App\Http\AuthArticles\IdentificationArticleInterface;
use App\Http\AuthArticles\JsonBodyArticleIdIdentification;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\CommentRepository;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\LikeRepository;
use App\Repositories\LikeRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/vendor/autoload.php';
Dotenv::createImmutable(__DIR__)->safeLoad();

$container = DIContainer::getInstance();

$container->bind(
    UserRepositoryInterface::class,
    UserRepository::class
);

$container->bind(
    ArticleRepositoryInterface::class,
    ArticleRepository::class
);

$container->bind(
    CommentRepositoryInterface::class,
    CommentRepository::class
);

$container->bind(
    LikeRepositoryInterface::class,
    LikeRepository::class
);

$container->bind(
    Connection::class,
    PdoConnectionDriver::getInstance($_SERVER['DSN_DATABASE'])
);

$container->bind(
    IdentificationInterface::class,
    JsonBodyUserEmailIdentification::class
);

$container->bind(
    IdentificationArticleInterface::class,
    JsonBodyArticleIdIdentification::class
);

$container->bind(
    AuthenticationInterface::class,
    PasswordAuthentication::class
);

$container->bind(
    PasswordAuthenticationInterface::class,
    PasswordAuthentication::class
);

$logger = new Logger('homework');

$isNeedLogToFile = (bool)$_SERVER['LOG_TO_FILES'];
$isNeedLogToConsole = (bool)$_SERVER['LOG_TO_CONSOLE'];

if ($isNeedLogToFile) {
    $logger
        ->pushHandler(
            new StreamHandler(
                __DIR__ . '/.Logs/homework.log'
            )
        )
        ->pushHandler(
            new StreamHandler(
                __DIR__ . '/.Logs/homework.error.log',
                level: Logger::ERROR,
                bubble: false,
            )
        );
}
if ($isNeedLogToConsole) {
    $logger
        ->pushHandler(
            new StreamHandler('php://stdout')
        );
}

$container->bind(
    LoggerInterface::class,
    $logger
);

return $container;
