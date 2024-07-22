<?php

namespace Tents\App\Controllers;

use Tents\Core\Controller;
use Tents\App\Models\BeachResortCollection;
use Tents\Core\Database\QueryBuilder;
use Tents\App\Models\BeachResort;
use Tents\App\Models\CityCollection;
use Tents\App\Models\ServiceBeachResortCollection;
use Tents\App\Models\ServiceCollection;


class BeachResortController extends Controller {

    public string $viewsDir;
    public $menu;

    public ?string $modelName = BeachResortCollection::class;
       
    public function index() {
        $titulo = "Balnearios";
        $beachResorts = $this->model->getEnable();
        $menu = $this->menu;
        $cityCollection = new CityCollection;
        $cityCollection ->setQueryBuilder($this->model->queryBuilder);
        $cities = $cityCollection->getAll();

        echo $this->twig->render('/portal-user/beachResort.index.view.twig', compact('menu','titulo','beachResorts', 'cities'));
    }

    public function get() {
        $beachResortId = $this->request->get('id');
        $beachResort = $this -> model -> get($beachResortId);
        $menu = $this->menu;
        $cityCollection = new CityCollection;
        $cityCollection ->setQueryBuilder($this->model->queryBuilder);
        $city = $cityCollection->get($beachResort->fields["city"]);
        $serviceBeachResortCollection = new ServiceBeachResortCollection;
        $serviceBeachResortCollection -> setQueryBuilder($this->model->queryBuilder);
        $servicesBeachResorts = $serviceBeachResortCollection-> getByBeachResort($beachResort->fields["id"]);
        $serviceCollection = new ServiceCollection;
        $serviceCollection -> setQueryBuilder($this->model->queryBuilder);
        $service_colection = [];
        $start_date = $this->request->get('start_date');
        $end_date = $this->request->get('end_date');

        foreach ($servicesBeachResorts as $servicesBeachResort){
            $service_colection[] = $serviceCollection->get($servicesBeachResort["service"])->fields;
        }
        echo $this->twig->render('/portal-user/beachResort.view.twig', compact('menu','beachResort',"city","service_colection",
                                                                  'start_date', 'end_date'));  
    }

    public function getByCity() {
        $cityId = $this->request->get('cityId');
        $beachResorts = $this->model->getByCity($cityId);
        
        header('Content-Type: application/json');
        echo json_encode($beachResorts, JSON_UNESCAPED_UNICODE);
    }

    public function getAllByCity() {
        $menu = $this->menu;
        $cityId = $this->request->get('id');
        $beachResorts = $this->model->getByCity($cityId);
        $cityCollection = new CityCollection;
        $cityCollection ->setQueryBuilder($this->model->queryBuilder);
        $city = $cityCollection->get($cityId);
        echo $this->twig->render('beachResortsCity.view.twig', compact('menu','beachResorts','city'));  
    }

    public function adminBeachResor() {
        session_start();
        if (isset($_SESSION['logueado'])) {
            $titulo = "Balnearios";
            $menu = $this->menuAdmin;
           // $beachResorts = $this->model->getAll();
            $beachResorts = $this->model->obtenerNombres();
        //    var_dump(cities);
        //    die;
            echo $this->twig->render('/portal-admin/adminBeachResor.view.twig',compact('menu','titulo','beachResorts'));
        }else {
            $mensajeError = 'Prueba';
            $menu = $this->menu;
            echo $this->twig->render('/portal-user/login.view.twig', ['mensajeError' => $mensajeError, 'menu' => $menu]);
        }
    }

    public function new() {
        $menu = $this->menu;
        $cityCollection = new CityCollection;
        $cityCollection ->setQueryBuilder($this->model->queryBuilder);
        $cities = $cityCollection->getAll();
        echo $this->twig->render('/portal-admin/newBeachResort.view.twig', compact('menu','cities'));
    }

    public function submit() {
        // Directorio donde se guardarán las imágenes subidas
        $uploadDir = '././imagenes/beachResorts/';

        $fileExtension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);

        // Nombre del archivo subido con la extensión original
        $uploadFile = $uploadDir . $_POST['name'] . '.' . $fileExtension;
    
        // Mover el archivo subido al directorio deseado
        move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile);

        $beachResort = new BeachResort;
        $beachResort->setName($_POST['name']);
        $beachResort->setDescription($_POST['description']);
        $beachResort->setCity($_POST['city']);
        $beachResort->setState(1);
        $beachResort->setLat($_POST['latitud']);
        $beachResort->setLon($_POST['longitud']);
        $beachResort->setImg($uploadFile);
        $this->model->insertBeachResort($beachResort);
        $this->adminBeachResor();
    }

    public function enable() {
        $id = $_POST['idbeachresort'];
        $state = 1;
        $this->model->updateBeachResortState($id,$state);
        header("Location: /adminBeachResor");
        exit();
    }

    public function disable() {
        $id = $_POST['idbeachresort'];
        $state = 2;
        $this->model->updateBeachResortState($id,$state);
        header("Location: /adminBeachResor");
        exit();
    }

    public function edit() {

        $beachResortId = $this->request->get('beachResort');
        $menu = $this->menuAdmin;
        //var_dump($beachResortId);
        
        // $beachResort = new BeachResort();
        // $beachResort.setId($beachResortId);

        $cityCollection = new CityCollection;
        $cityCollection ->setQueryBuilder($this->model->queryBuilder);
        $cities = $cityCollection->getAll();

        $beachResort = $this -> model -> obtenerCiudad($beachResortId);
        // var_dump($beachResort);
        // die;
        echo $this->twig->render('/portal-admin/editBeachResort.view.twig', compact('cities','beachResort'));

    }

    public function set() {

    }

}