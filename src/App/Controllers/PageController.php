<?php

namespace  Paw\App\Controllers;

class PageController
{
    public string $viewsDir;
    public $menu;

    public function __construct()
    {
        $this-> viewsDir = __DIR__ . "/../views/"; 
        $this-> menu = [
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
    }

    public function index()
    {
        $titulo = 'Tens';
        require $this-> viewsDir . 'index.view.php';
    }
    public function servicies()
    {
        $titulo = 'Servicios';
        require $this-> viewsDir . 'services.view.php';
    }
    public function contact()
    {
        $titulo = 'Contacto';
        require $this-> viewsDir . 'contact.view.php';

    }

    public function contactProccess()
    {
        $formulario = $_POST;
        //Hacer algo
        require $this-> viewsDir . 'contact.view.php';

    }
 
}