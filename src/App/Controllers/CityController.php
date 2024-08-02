<?php

namespace Tents\App\Controllers;

use Tents\Core\Controller;
use Tents\App\Models\CityCollection;
use Tents\Core\Database\QueryBuilder;
use Tents\App\Models\City;

use Exception;


class CityController extends Controller {

    public string $viewsDir;
    public $menu;

    public ?string $modelName = CityCollection::class;
       
    public function index() {
        $cities = $this->model->getAll();
        $menu = $this->menu;
        echo $this->twig->render('beachResort.index.view.twig', compact('menu','titulo','beachResorts'));
    }

    public function getAll() {
        try {
            $cities = $this->model->getAll();
            
            $ciudades = [];
            foreach ($cities as $city) {
                $ciudades[] = $city->fields;
            }
    
            $response = [
                'success' => true,
                'data' => $ciudades
            ];
    
            header('Content-Type: application/json');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            // Manejo de errores
            $response = [
                'success' => false,
                'message' => 'Error al obtener las ciudades: ' . $e->getMessage()
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
    
    public function get() {
        $beachResortId = $this->request->get('id');
        $beachResort = $this -> model -> get($beachResortId);
        $menu = $this->menu;
        echo $this->twig->render('beachResort.view.twig', compact('menu','beachResort'));  
    }

    public function adminCity() {

        if ($this -> isLogged()) {
            $titulo = "Ciudades";
            $menu = $this->menuAdmin;
            $cities = $this->model->getAll();
            echo $this->twig->render('/portal-admin/adminCity.view.twig',compact('menu','titulo','cities'));
        }else {
            $mensajeError = 'Debe iniciar sesión';
            $menu = $this->menu;
            echo $this->twig->render('login.view.twig', ['mensajeError' => $mensajeError, 'menu' => $menu]);
        }
    
    }

    public function new($error='') {

        if ($this -> isLogged()) {

            $menu = $this->menuAdmin;

            if ($error) {
                echo $this->twig->render('/portal-admin/newCity.view.twig', compact('menu','error'));
            } else {
                echo $this->twig->render('/portal-admin/newCity.view.twig', compact('menu'));
            }
        } else {
            header("Location: /");
        }

    }

    public function submit() {

        if ($this -> isLogged()) {

            $uploadDir = '././imagenes/cities/';

            try {
                if (empty($_FILES['imagen_perfil']['tmp_name'])) {
                    throw new Exception("Debes adjuntar una imagen de la ciudad");
                }

                $imagenCiudad = $_FILES['imagen_perfil']['name'];
                $extensionCiudad = strtolower(pathinfo($imagenCiudad, PATHINFO_EXTENSION));
                $formatos_permitidos_ciudad = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($extensionCiudad, $formatos_permitidos_ciudad)) {
                    throw new Exception("Formato de imagen de perfil no permitido");
                }

                // Nombre del archivo subido con la extensión original
                $uploadFile = $uploadDir . $_POST['name'] . '.' . $extensionCiudad;
        
                // Mover el archivo subido al directorio deseado
                move_uploaded_file($_FILES['imagen_perfil']['tmp_name'], $uploadFile);

                $city = new City;
                $city->setName($_POST['name']);
                $city->setLat($_POST['latitud']);
                $city->setLon($_POST['longitud']);
                $city->setImg($uploadFile);
                $this->model->insertCity($city);
                header("Location: /adminCity");
                exit();

            } catch (Exception $e) {
                $error = $e -> getMessage();
                $this -> new($error);
            }
        } else {
            header("Location: /");
        }

    }

    public function edit() {

        if ($this -> isLogged()) {
            $cityId = $this->request->get('id');
            $cityCollection = new CityCollection;
            $cityCollection ->setQueryBuilder($this->model->queryBuilder);
            $city = $cityCollection->get($cityId);
            $menu = $this->menuAdmin;
    
            echo $this->twig->render('/portal-admin/editCity.view.twig', compact('city', 'menu'));
        }
        else {
            header("Location: /");
        }

    }

    public function editCity() {

        if ($this -> isLogged()) {
            $idCity = $_POST['id'];
            $nameCity = $_POST['name'];
            $provinceCity = $_POST['province'];
            $lat = $_POST['latitud'];
            $lon = $_POST['longitud'];

            $city = new City;
            $city -> setId($idCity);
            $city -> setName($nameCity);
            $city -> setProvince($provinceCity);
            $city -> setLat($lat);
            $city -> setLon($lon);

            $this->model->updateCity($city);
            
            header("Location: /adminCity");
            exit();
        } else {
            header("Location: /");
        }
    }
}