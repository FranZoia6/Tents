<?php

namespace Tents\Core;

use Tents\Core\Database\QueryBuilder;
use Tents\Core\Model;
use Tents\Core\Request;

class Controller {

    public string $viewsDir;
    public ?string $modelName = null;
    public $model;
    public $loader = null;
    public $twig = null;
    protected Request $request;

    public function __construct(Request $request) {
        global $connection, $log;
        $this->request = $request;
        $this-> viewsDir = __DIR__ . "/../App/views/"; 
        $this->loader = new \Twig\Loader\FilesystemLoader($this->viewsDir);
		$this->twig =new \Twig\Environment($this->loader,[]);
        @$this-> menu = [
            [    
                "href" => "/beachResortIndex",
                "name" => "Balnearios",
            ]
        ];
        @$this->menuAdmin = [

            [
                "href" => "/adminCity",
                "name" => "Ciudades",
            ],
            [
                "href" => "/adminBeachResor",
                "name" => "Balnearios",
            ],
            [
                "href" => "/adminReservation",
                "name" => "Reservas",
            ],
            [
                "href" => "/?sesion=cerrar",
                "name" => "Cerrar sesión",
            ]
        ];

        if(!is_null($this->modelName)){
			$qb = new QueryBuilder($connection,$log);
			$model = new $this->modelName;
			$model->setQueryBuilder($qb);
			$this->setModel($model);
		}

    }
    
    public function setModel(Model $model) {
        $this -> model = $model;
    }

    public function isLogged() {
        //session_start();
        $hayLogin = isset($_SESSION['login']) && !empty($_SESSION['login']);
        return $hayLogin;
    }


}