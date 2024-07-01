<?php

namespace Tents\Core;

use Tents\Core\Database\QueryBuilder;
use Tents\Core\Model;

class Controller {

    public string $viewsDir;
    public ?string $modelName = null;
    public $model;
    public $loader = null;
    public $twig = null;

    public function __construct(){
        global $connection, $log;
        $this-> viewsDir = __DIR__ . "/../App/views/"; 
        $this->loader = new \Twig\Loader\FilesystemLoader($this->viewsDir);
		$this->twig =new \Twig\Environment($this->loader,[]);
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
            [    
                "href" => "/beachResort",
                "name" => "Beach Resort",
            ],
        
        
        ];

    }

    
    public function setModel(Model $model) {
        $this -> model = $model;
    }


}