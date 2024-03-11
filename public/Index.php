<?php

require __DIR__ . "/../src/bootstrap.php";

use Paw\Core\Router;


//$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
//$log->info("Petición a: {$path}");

$router->direct($request);

/*
try {
    $router->direct($path);
}catch(RouteNotFoundException $e){
    $router->direct('not_found');
    $log->info('Not found 404', ["Path"=>$path]);
}catch(Exception $e){
    $router->direct('internal_error');
    $log->error('Status Code 500', ["Error"=>$e]);
} */
