<?php

require __DIR__ . "/../vendor/autoload.php";

use Paw\App\Controllers\ErrorController;
use Paw\App\Controllers\PageController;

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops-> register();

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$controller = new PageController; 

if ($path == '/'){
    $controller -> index();
}else if ($path == '/servicies'){
    $controller-> servicies();
}else if ($path == '/contact'){
   $controller->contact();
}else{
    $controller = new ErrorController;
    $controller ->notFound();
}
