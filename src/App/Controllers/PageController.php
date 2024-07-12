<?php

namespace  Tents\App\Controllers;
use Tents\Core\Controller;
use Tents\Core\Database\QueryBuilder;
use Tents\App\Models\CityCollection;

class PageController extends Controller
{
    public string $viewsDir;
    public $menu;

    public ?string $modelName = CityCollection::class;

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
        $cityCollection = new CityCollection;
        $cityCollection ->setQueryBuilder($this->model->queryBuilder);
        $cities = $cityCollection->getAll();
        echo $this->twig->render('index.view.twig', compact('menu','titulo','cities'));
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

    public function contactProccess()
    {
        $formulario = $_POST;
        //Hacer algo
        require $this-> viewsDir . 'contact.view.php';

    }

    public function reservationPersonalData() {
        echo $this->twig->render('reservationPersonalData.view.twig');
    }
 
}