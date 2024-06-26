<?php

namespace  Paw\App\Controllers;
use Paw\Core\Controller;

class PageController extends Controller
{
    public string $viewsDir;
    public $menu;

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