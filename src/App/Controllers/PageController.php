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
        $ciudades = ['Mar del Plata', 'Ciudad 2', 'Ciudad 3'];
        $balnearios = ['Alfonsina Storni', 'Balneario 2', 'Balneario 3'];

        session_start();

        // Cerrar Sesion
        
        $cerrarSesion = isset($_GET['sesion']);
        $haySesion = session_status() == PHP_SESSION_ACTIVE;

        if ($cerrarSesion && $haySesion) {
            $_SESSION = [];
            setcookie(session_name(), '', time() - 10000);
            session_destroy();
        }

        echo $this->twig->render('index.view.twig', compact('menu','titulo','ciudades','balnearios'));
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
        $menu = $this->menu;
        $hayLogin = isset($_SESSION['login']) && !empty($_SESSION['login']);

        $usuario = '';

        if ($hayLogin) {
            $usuario = $_SESSION['login'];
        }
        echo $this->twig->render('login.view.twig', ['hayLogin' => $hayLogin, 
                                'usuario' => $usuario, 'menu' => $menu],
                                );
    }

    public function balnearios() {
        echo $this->twig->render('/portal-admin/balnearios.view.twig');
    }

    public function contactProccess()
    {
        $formulario = $_POST;
        //Hacer algo
        require $this-> viewsDir . 'contact.view.php';

    }
 
}