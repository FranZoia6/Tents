<?php
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
    echo "Page Not Found";
}
