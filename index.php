<?php
use Respect\Validation\Validator as v;

session_start();

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config/config.php";

$app = new Slim\App($config);
$container = $app->getContainer();
require_once __DIR__ . "/config/constants.php";

$container['auth'] = new App\Auth\Auth();
$container['flash'] = new Slim\Flash\Messages();
$container['validator'] = new App\Validation\Validator();
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/resources/views/', ['cache' => false]);
    $view->addExtension(new \Slim\Views\TwigExtension($container->router, $container->request->getUri()));
    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user()
    ]);
    $view->getEnvironment()->addGlobal('flash', $container->flash);
    return $view;
};
$container['csrf'] = function ($container) {
    return new \Slim\Csrf\Guard;
};

$capsule = new Illuminate\Database\Capsule\Manager();
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$app->add(new \App\Middleware\ValidationErrosMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
$app->add($container->csrf);

v::with('App\\Validation\\Rules\\');
require_once __DIR__ . '/routes/web.php';
$app->run();
