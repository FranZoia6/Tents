<?php

namespace  Tents\App\Controllers;
use Tents\Core\Controller;

class PageController extends Controller
{
    public string $viewsDir;
    public $menu;

    public function index()
    {
        $titulo = 'Tents';
        $menu = $this->menu;
        echo $this->twig->render('index.view.twig', compact('menu','titulo'));
    }
    public function servicies()
    {
        $titulo = 'Servicios';
        $menu = $this->menu;
        echo $this->twig->render('services.view.twig', compact('menu','titulo'));
    }

    public function contact()
    {
        $titulo = 'Contacto';
        $menu = $this->menu;
        echo $this->twig->render('contact.view.twig', compact('menu','titulo'));
    }

    public function contactProccess()
    {
        $formulario = $_POST;
        //Hacer algo
        require $this-> viewsDir . 'contact.view.php';

    }
 
}