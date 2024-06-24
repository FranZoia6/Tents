<?php

require __DIR__ . "/../vendor/autoload.php";

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Paw\Core\Exceptions\RouteNotFoundException;
use Paw\Core\Request;
use Paw\Core\Router;

$log = new Logger('mvc-app');
$log->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Level::Debug));

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops-> register();

$router = new Router;
$router -> setLogger($log);
$router->get('/','PageController@index');
$router->get('/servicies','PageController@servicies');
$router->get('/contact','PageController@contact');
$router->get('not_found', 'ErrorController@notFound');
$router->get('internal_error','ErrorController@internalError');

$request = new Request;
