<?php
declare(strict_types=1);
require __DIR__ . '/autoload.php';

/**
 * Start de PHP session.
 * Zonder dit werkt $_SESSION niet.
 */
session_start();

use Admin\Controllers\DashboardController;
use Admin\Controllers\PostsController;
use Admin\Controllers\ErrorController;
use Admin\Controllers\AuthController;
use Admin\Core\Router;
use Admin\Core\Auth;
use Admin\Repositories\PostsRepository;
use Admin\Repositories\UsersRepository;
use Admin\Models\StatsModel;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/php/minicms-pro/admin';
if (str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

$uri = rtrim($uri, '/');
$uri = $uri === '' ? '/' : $uri;

$publicRoutes = ['/login'];
if (!Auth::check() && !in_array($uri, $publicRoutes, true)) {
    header('Location: /php/minicms-pro/admin/login');
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

$router = new Router();

/**
 * setNotFoundHandler()
 *
 * Doel:
 * - Zorgt dat elke onbekende URL een nette 404 pagina krijgt via ErrorController.
 */
$errorController = new ErrorController();
$router->setNotFoundHandler(function (string $requestedUri) use
($errorController): void {
    $errorController->notFound($requestedUri);
});

/**
 * Auth
 */
$router->get('/login', function (): void {
    (new AuthController(UsersRepository::make()))->showLogin();
});
$router->post('/login', function (): void {
    (new AuthController(UsersRepository::make()))->login();
});
$router->post('/logout', function (): void {
    (new AuthController(UsersRepository::make()))->logout();
});

/**
 * Delete (confirm + action)
 */
$router->get('/posts/{id}/delete', function (int $id): void {
    if (!Auth::isAdmin()) {
        header('Location: /php/minicms-pro/admin/posts');
        exit;
    }
    (new PostsController(PostsRepository::make()))->deleteConfirm($id);
});
$router->post('/posts/{id}/delete', function (int $id): void {
    if (!Auth::isAdmin()) {
        header('Location: /php/minicms-pro/admin/posts');
        exit;
    }
    (new PostsController(PostsRepository::make()))->delete($id);
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

/**
 * Create form
 * Zonder deze route krijg je 404 op /posts/create.
 */
$router->get('/posts/create', function (): void {
    (new PostsController(PostsRepository::make()))->create();
});

/**
 * Store post (POST)
 * Zonder deze route kan het formulier niet opslaan.
 */
$router->post('/posts/store', function (): void {
    (new PostsController(PostsRepository::make()))->store();
});

/**
 * Edit + Update
 * Deze routes zijn specifieker dan /posts/{id}, dus ze moeten erboven staan.
 */
$router->get('/posts/{id}/edit', function (int $id): void {
    (new PostsController(PostsRepository::make()))->edit($id);
});
$router->post('/posts/{id}/update', function (int $id): void {
    (new PostsController(PostsRepository::make()))->update($id);
});

/**
 * Show single post
 * Deze route moet onder edit/update staan.
 */
$router->get('/posts/{id}', function (int $id): void {
    (new PostsController(PostsRepository::make()))->show($id);
});

$router->dispatch($uri, $method);