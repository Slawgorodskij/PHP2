<?php

use App\Exceptions\HttpException;
use App\Http\Actions\Article\CreateArticle;
use App\Http\Actions\Article\DeleteArticle;
use App\Http\Actions\Article\FindByArticle;
use App\Http\Actions\Comment\CreateComment;
use App\Http\Actions\Comment\DeleteComment;
use App\Http\Actions\Like\CreateLike;
use App\Http\Actions\User\CreateUser;
use App\Http\Actions\User\FindByEmail;
use App\Http\ErrorResponse;
use App\Http\Request;
use Psr\Log\LoggerInterface;

$container = require __DIR__ . '/bootstrap.php';

/**
 * @var LoggerInterface $logger
 */
$logger = $container->get(LoggerInterface::class);

$request = new Request(
    $_GET,
    $_SERVER,
    // Читаем поток, содержащий тело запроса
    file_get_contents('php://input'),
);

try {
    $path = $request->path();
} catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        '/user/show' => FindByEmail::class,
        '/article/show' => FindByArticle::class,
        '/article/delete' => DeleteArticle::class,
        '/comment/delete' => DeleteComment::class,
    ],
    'POST' => [
        '/user/create' => CreateUser::class,
        '/article/create' => CreateArticle::class,
        '/comment/create' => CreateComment::class,
        '/like/create' => CreateLike::class,
    ],
];

if (!array_key_exists($method, $routes)) {
    $logger->info(sprintf('Клиент с IP-адресом :%s пытался получить несуществующий роут', $_SERVER['REMOTE_ADDR']));
    (new ErrorResponse('Metod not found'))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Path not found'))->send();
    return;
}

$actionClassName = $routes[$method][$path];

try {
    $action = $container->get($actionClassName);
    $response = $action->handle($request);
} catch (Exception $e) {
    $logger->error($e->getMessage());
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();
