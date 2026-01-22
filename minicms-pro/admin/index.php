<?php
declare(strict_types=1);
require __DIR__ . '/autoload.php';

use Admin\Controllers\DashboardController;
use Admin\Controllers\PostsController;
use Admin\Controllers\ErrorController;
use Admin\Core\Router;
use Admin\Repositories\PostsRepository;
use Admin\Models\PostsModel;
use Admin\Models\StatsModel;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/php/minicms-pro/admin';
if (str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

$uri = rtrim($uri, '/');
$uri = $uri === '' ? '/' : $uri;

$method = $_SERVER['REQUEST_METHOD'];

$router = new Router();

/**
 * setNotFoundHandler()
 *
 * Doel:
 * - Zorgt dat elke onbekende URL een nette 404 pagina krijgt via
ErrorController.
 */
$errorController = new ErrorController();
$router->setNotFoundHandler(function (string $requestedUri) use
($errorController): void {
    $errorController->notFound($requestedUri);
});

/**
 * Dashboard
 */
$router->get('/', function (): void {
    (new DashboardController(new StatsModel()))->index();
});

/**
 * Posts
 */
$router->get('/posts', function (): void {
    (new PostsController(PostsRepository::make()))->index();
});

$router->get('/posts/{id}', function (int $id): void {
    (new PostsController(PostsRepository::make()))->show($id);
});

$router->dispatch($uri, $method);