<?php

require __DIR__ . "/../vendor/autoload.php";

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Paw\Core\Exceptions\RouteNotFoundException;
use Paw\Core\Request;
use Paw\Core\Router;
use Paw\Core\Database\ConnectionBuilder;
use Paw\Core\Config;
use Dotenv\Dotenv;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv -> load();

$log = new Logger('mvc-app');
$log->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Level::Debug));

$config = new Config;

// Conexion a BD
$connectionBuilder = new ConnectionBuilder;
$connectionBuilder -> setLogger($log);
$connection = $connectionBuilder-> make($config);

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops-> register();

$router = new Router;
$router -> setLogger($log);
$router->get('/','PageController@index');
$router->get('/servicies','PageController@servicies');
$router->get('/contact','PageController@contact');
$router->post('/contact','PageController@contactProccess');
$router->get('not_found', 'ErrorController@notFound');
$router->get('internal_error','ErrorController@internalError');

$request = new Request;
