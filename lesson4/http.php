<?php

use App\Http\Actions\Article\CreateArticle;
use App\Http\Actions\Article\DeleteArticle;
use App\Http\Actions\Article\FindByArticle;
use App\Http\Actions\Comment\CreateComment;
use App\Http\Actions\Comment\DeleteComment;
use App\Http\Actions\CreateUser;
use App\Http\Actions\FindByEmail;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Exceptions\HttpException;

require_once __DIR__ . '/vendor/autoload.php';

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
);

try {
    $path = $request->path();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        '/user/show' => new FindByEmail(),
        '/article/show' => new FindByArticle(),
        '/article/delete' => new DeleteArticle(),
        '/comment/delete' => new DeleteComment(),
    ],
    'POST' => [
        '/user/create' => new CreateUser(),
        '/article/create' => new CreateArticle(),
        '/comment/create' => new CreateComment(),
    ],
];

if (!array_key_exists($method, $routes)) {
    (new ErrorResponse('Not found'))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Not found'))->send();
    return;
}

// Выбираем действие по методу и пути
$action = $routes[$method][$path];

try {
    $response = $action->handle($request);
} catch (Exception $e) {
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();
