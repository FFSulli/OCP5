<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use App\Service\Router;
use App\Service\Http\Request;
use App\Service\DotEnv\DotEnv;

$dotEnv = new DotEnv();
$environment = $dotEnv->get('APP_ENVIRONMENT');

if ($environment === 'dev')
{
    $whoops = new \Whoops\Run();
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
    $whoops->register();
}

$request = new Request($_GET, $_POST, $_FILES, $_SERVER);
$router = new Router($request, $dotEnv);
$response = $router->run();
$response->send();

//$newRouter = new \App\Service\NewRouter(require __DIR__ . "/../config/routes.php");

