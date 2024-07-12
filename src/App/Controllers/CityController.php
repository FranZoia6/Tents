<?php

namespace Tents\App\Controllers;

use Tents\Core\Controller;
use Tents\App\Models\CityCollection;
use Tents\Core\Database\QueryBuilder;
use Tents\App\Models\City;


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
        session_start();
        if (isset($_SESSION['logueado'])) {
            $titulo = "Ciudades";
            $menu = $this->menuAdmin;
            $cities = $this->model->getAll();
            echo $this->twig->render('/portal-admin/adminCity.view.twig',compact('menu','titulo','cities'));
        }else {
            $mensajeError = 'Prueba';
            $menu = $this->menu;
            echo $this->twig->render('login.view.twig', ['mensajeError' => $mensajeError, 'menu' => $menu]);
        }
    }

    public function new() {
        $menu = $this->menu;
        echo $this->twig->render('/portal-admin/newCity.view.twig', compact('menu'));
    }

    public function submit() {
        $city = new City;
        $city->setName($_POST['name']);
        $this->model->insertCity($city);
        $this->adminCity();
    }

    public function edit() {

    }

    public function set() {

    }

}