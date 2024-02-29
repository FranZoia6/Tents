<?php

require __DIR__ . "/../vendor/autoload.php";

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops-> register();

$menu = [
    [
        "href" => "/",
        "name" => "Home",
    ],
    [
        "href" => "/servicies",
        "name" => "Servicios",
    ],
    [    
        "href" => "/contact",
        "name" => "Contactos",
    ],

];

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if ($path == '/'){
    require  __DIR__ . '/../src/Views/index.view.php';
}else if ($path == '/servicies'){
    require  __DIR__ . '/../src/Views/services.view.php';
    var_dump($path);
    die;
}else if ($path == '/contact'){
    require  __DIR__ . '/../src/Views/contact.view.php';
}else{
    http_response_code(404);
    require  __DIR__ . '/../src/Views/not-found.view.php';
}
