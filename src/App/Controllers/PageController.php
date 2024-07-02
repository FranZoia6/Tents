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

        session_start();

        // Cerrar Sesion
        $cerrarSesion = isset($_GET['sesion']);
        $haySesion = session_status() == PHP_SESSION_ACTIVE;

        if ($cerrarSesion && $haySesion) {
            $_SESSION = [];
            setcookie(session_name(), '', time() - 10000);
            session_destroy();
        }

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

    public function login()
    {
        session_start();
        $titulo = 'Iniciar sesión';
        $menu = $this->menu;
        $hayLogin = isset($_SESSION['login']) && !empty($_SESSION['login']);

        $dni = '';

        if ($hayLogin) {
            $dni = $_SESSION['login'];
        }
        echo $this->twig->render('login.view.twig', ['hayLogin' => $hayLogin, 'dni' => $dni]);
    }

    public function contactProccess()
    {
        $formulario = $_POST;
        //Hacer algo
        require $this-> viewsDir . 'contact.view.php';

    }
 
}