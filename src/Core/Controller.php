<?php

namespace Tents\Core;

use Tents\Core\Database\QueryBuilder;
use Tents\Core\Model;

class Controller {

    public string $viewsDir;
    public ?string $modelName = null;
    public $model;

    public function __construct(){
        global $connection, $log;
        $this-> viewsDir = __DIR__ . "/../App/views/"; 
        $this-> menu = [

            [
                "href" => "/",
                "name" => "Home",
            ],
            [
                "href" => "/servicies",
                "name" => "Servicios",
            ],
            [    
                "href" => "/contact",
                "name" => "Contactos",
            ],
        
        ];

    }

    
    public function setModel(Model $model) {
        $this -> model = $model;
    }


}