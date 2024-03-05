<?php

require __DIR__ . "/../vendor/autoload.php";

use Paw\App\Controllers\ErrorController;
use Paw\App\Controllers\PageController;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('mvc-app');
$log->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Level::Debug));

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops-> register();

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$log->info("Petición a: {$path}");
$controller = new PageController; 

if ($path == '/'){
    $controller -> index();
    $log->info('Respuesta exitosa: 200');
}else if ($path == '/servicies'){
    $controller-> servicies();
    $log->info('Respuesta exitosa: 200');
}else if ($path == '/contact'){
   $controller->contact();
   $log->info('Respuesta exitosa: 200');
}else{
    $controller = new ErrorController;
    $controller ->notFound();
    $log->info('Not found 404');
}
