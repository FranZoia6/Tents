<?php

namespace Tents\App\Controllers;

use Tents\Core\Controller;
use Tents\App\Models\BeachResortCollection;
use Tents\Core\Database\QueryBuilder;
use Tents\App\Models\BeachResort;
use Tents\App\Models\CityCollection;
use Tents\App\Models\ServiceBeachResortCollection;
use Tents\App\Models\ServiceCollection;
use Tents\App\Models\UnitCollection;
use Tents\App\Models\Unit;
use Exception;

use SimpleXMLElement;


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

        try {
            // Verificar si se subió un archivo
            if (empty($_FILES['archivo']['tmp_name'])) {
                throw new Exception("Debes adjuntar un archivo");
            }
        
            // Validar la extensión del archivo
            $formatos_permitidos = ['svg'];
            $archivo = $_FILES['archivo']['name'];
            $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
        
            if (!in_array($extension, $formatos_permitidos)) {
                throw new Exception("Formato no permitido");
            }
        
            // Nombre del archivo subido con la extensión original
            $uploadFile = $uploadDir . $_POST['name'] . '.' . $extension;
        
            // Mover el archivo subido al directorio de destino
            if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $uploadFile)) {
                throw new Exception("Error al mover el archivo subido");
            }
        
            echo "El archivo " . htmlspecialchars(basename($_FILES['archivo']['name'])) . " ha sido subido correctamente.<br>";
        
            // Procesar el SVG para contar elementos
            $svg_content = file_get_contents($uploadFile); // Obtener contenido del archivo SVG
        
            // Crear objeto SimpleXMLElement desde el contenido del SVG
            $svg = new SimpleXMLElement($svg_content);
        
            // Contador para <ellipse>
            $sombrillas_count = 0;
        
            // Contar todos los elementos <ellipse> dentro del SVG
            foreach ($svg->xpath('//svg:ellipse') as $sombrilla) {
                // Incrementar el contador de elipses
                $sombrillas_count++;
            }
        
            echo "Número de sombrillas: " . $sombrillas_count . "<br>";
        
            // Contador para <rect> con id de un solo caracter
            $carpas_count = 0;
        
            // Contar todos los <rect> dentro del SVG que tienen un id compuesto únicamente por dígitos
            foreach ($svg->xpath('//svg:rect') as $rect) {
                // Verificar si el id del rect es un número utilizando preg_match
                $id = (string) $rect['id'];
                if (preg_match('/^\d+$/', $id)) {
                    // Incrementar el contador de rects con id que es solo un número
                    $carpas_count++;
                }
            }

            echo "Número de carpas: " . $carpas_count . "<br>";

            // Aquí puedes guardar los resultados en la base de datos u hacer cualquier otra operación necesaria
            
            $beachResort = new BeachResort;
            $beachResort->setName($_POST['name']);
            $beachResort->setDescription($_POST['description']);
            $beachResort->setCity($_POST['city']);
            $beachResort->setState(1);
            $beachResort->setLat($_POST['latitud']);
            $beachResort->setLon($_POST['longitud']);
            $beachResort->setImg($uploadFile);
            $this->model->insertBeachResort($beachResort);

            $newBeachResort = $this -> model -> getByName($beachResort -> fields['name']);

            // Insertar unidades de carpas

            $unitCollection = new UnitCollection;
            $unitCollection ->setQueryBuilder($this->model->queryBuilder);

            $number = 1;

            // Iterar $carpas_count veces
            for ($i = 1; $i <= $carpas_count; $i++) {
                
                $carpa = new Unit;
                $carpa -> setBeachResort($newBeachResort[0]['id']);
                $carpa -> setShade(1);
                $carpa -> setNumber($number);
                $carpa -> setPrice($_POST['precioCarpas']);
                $unitCollection -> insertUnit($carpa);
                $number++;
            }

            // Iterar $sombrillas_count veces
            for ($i = 1; $i <= $sombrillas_count; $i++) {
    
                $sombrilla = new Unit;
                $sombrilla -> setBeachResort($newBeachResort[0]['id']);
                $sombrilla -> setShade(2);
                $sombrilla -> setNumber($number);
                $sombrilla -> setPrice($_POST['precioSombrillas']);
                $unitCollection -> insertUnit($sombrilla);
                $number++;
                
            }

            $this->adminBeachResor();

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        
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