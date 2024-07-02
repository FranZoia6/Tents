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
        $beachResorts = $this->model->getAll();
        $menu = $this->menu;
        echo $this->twig->render('beachResort.index.view.twig', compact('menu','titulo','beachResorts'));
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