<?php

namespace  Tents\App\Controllers;
use Tents\Core\Controller;
use Tents\Core\Database\QueryBuilder;
use Tents\App\Models\CityCollection;
use Tents\App\Models\BeachResortCollection; 
use Tents\App\Models\ReservationCollection;

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

        $beachResortCollection = new BeachResortCollection;
        $beachResortCollection -> setQueryBuilder($this->model->queryBuilder);

        foreach ($cities as $city) {
            $beachResorts = $beachResortCollection->getByCity($city ->fields["id"]);
            $city-> fields["cant_beach_resorts"] = count($beachResorts);
        }

        $search = true;
        echo $this->twig->render('/portal-user/index.view.twig', compact('menu','titulo','cities', 'search'));
    }

    public function contact()
    {
        $titulo = 'Contacto';
        $menu = $this->menu;
        echo $this->twig->render('/portal-user/contact.view.twig', compact('menu','titulo'));
    }

    public function about()
    {
        $titulo = 'Sobre Nosotros';
        $menu = $this->menu;
        echo $this->twig->render('/portal-user/about.view.twig', compact('menu','titulo'));
    }

    public function privacyPolicies()
    {
        $titulo = 'Politicas de Privacidad';
        $menu = $this->menu;
        echo $this->twig->render('/portal-user/privacyPolicies.view.twig', compact('menu','titulo'));
    }

    public function termsOfServices()
    {
        $titulo = 'Términos de Servicio';
        $menu = $this->menu;
        echo $this->twig->render('/portal-user/termsOfService.view.twig', compact('menu','titulo'));
    }


    public function login()
    {
        session_start();
        $menu = $this->menu;
        $hayLogin = isset($_SESSION['login']) && !empty($_SESSION['login']);

        $usuario = '';

        if ($hayLogin) {
            $usuario = $_SESSION['login'];

            $titulo = "Admin";
            $menu = $this->menuAdmin;
            $cityCollection = new CityCollection;
            $cityCollection->setQueryBuilder($this->model->queryBuilder);
            $cities = $cityCollection->getAll();
            $numberOfCities = count($cities);
            
            $beachResortCollection = new BeachResortCollection;
            $beachResortCollection->setQueryBuilder($this->model->queryBuilder);
            $beachResorts = $beachResortCollection->getAll();
            $numberOfBeachResorts = count($beachResorts);
            
            $reservationCollection = new ReservationCollection;
            $reservationCollection->setQueryBuilder($this->model->queryBuilder);
            $reservations = $reservationCollection->getAll();
            $numberOfReservations = count($reservations);

            echo $this->twig->render('/portal-admin/inicio-usuario.view.twig',compact('menu','titulo','numberOfCities','numberOfBeachResorts','numberOfReservations'));
        } else {
            echo $this->twig->render('/portal-user/login.view.twig', ['hayLogin' => $hayLogin, 
            'usuario' => $usuario, 'menu' => $menu],
            );
        }

    }

    public function contactProccess()
    {
        $formulario = $_POST;
        require $this-> viewsDir . '/portal-user/contact.view.php';
    }

    public function reservationPersonalData() {
        echo $this->twig->render('/portal-user/reservationPersonalData.view.twig');
    }
 
}