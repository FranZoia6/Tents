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
        global $request;
        $beachResortId = $request -> get('id');
        $beachResort = $this -> model -> get($beachResortId);
        $menu = $this->menu;
        echo $this->twig->render('beachResort.view.twig', compact('menu','beachResort'));  
    }

    public function edit() {

    }

    public function set() {

    }

}