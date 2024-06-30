<?php

namespace Paw\App\Controllers;

use Paw\Core\Controller;
use Paw\App\Models\BeachResortCollection;
use Paw\Core\Database\QueryBuilder;


class BeachResortController extends Controller {

    public ?string $modelName = BeachResortCollection::class;
       
    public function index() {
        $titulo = "Balnearios";
        $authors = $this -> model -> getAll();
        require $this -> viewsDir . 'authors.index.view.php';
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