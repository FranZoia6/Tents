<?php

require __DIR__ . "/../vendor/autoload.php";

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Tents\Core\Exceptions\RouteNotFoundException;
use Tents\Core\Request;
use Tents\Core\Router;
use Tents\Core\Database\ConnectionBuilder;
use Tents\Core\Config;
use Dotenv\Dotenv;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv -> load();

$config = new Config;

$log = new Logger('mvc-app');
$handler = new StreamHandler($config -> get("LOG_PATH"));
$handler -> setLevel($config -> get("LOG_LEVEL"));
$log->pushHandler($handler);

// Conexion a BD
$connectionBuilder = new ConnectionBuilder;
$connectionBuilder -> setLogger($log);
$connection = $connectionBuilder-> make($config);

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops-> register();

$request = new Request;

$router = new Router;
$router -> setLogger($log);
$router->get('/','PageController@index');
$router->get('/servicies','PageController@servicies');
$router->get('/contact','PageController@contact');
$router->get('/beachResortIndex','BeachResortController@index');
$router->get('/beachResort','BeachResortController@get');
$router->post('/contact','PageController@contactProccess');
$router ->get('/login', 'PageController@login');

$router ->get('/cities', 'CityController@getAll');

$router ->get('/resorts', 'BeachResortController@getByCity');


$router -> post('/portal-admin', 'UserController@loginValidar');
$router -> get('/portal-admin/balnearios', 'PageController@balnearios');

$router->get('not_found', 'ErrorController@notFound');
$router->get('internal_error','ErrorController@internalError');

