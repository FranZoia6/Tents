<?php

namespace Tents\App\Controllers;

use Tents\Core\Controller;
use Tents\App\Models\BeachResortCollection;
use Tents\Core\Database\QueryBuilder;
use Tents\App\Models\BeachResort;


class BeachResortController extends Controller {

    public string $viewsDir;
    public $menu;

    public ?string $modelName = BeachResortCollection::class;
       
    public function index() {
        $titulo = "Balnearios";
        $beach_resorts = $this->model->getAll();
        $menu = $this->menu;
        echo $this->twig->render('beachResort.view.twig', compact('menu','titulo','beachResort'));
    }

    public function get() {
        global $request;
        $beachResortId = $request -> get('Id');
        $beachResort = $this -> model -> get($beachResortId);
        $titulo = 'Balneario';
        require $this -> viewsDir . 'authors.show.view.php';   
    }

    public function edit() {

    }

    public function set() {

    }

}