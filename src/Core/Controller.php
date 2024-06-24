<?php

namespace Paw\Core;
include 'twig.php';
use Paw\Core\Database\QueryBuilder;
use Paw\Core\Model;

class Controller {

    public string $viewsDir;
    public ?string $modelName = null;
    public $model;
    protected $parts = ['menu', 'subMenuInstitucional', 'subMenuInformacionUtil'];
    protected $twig;

    public function __construct(){
        global $connection, $log;
        $this -> viewsDir = __DIR__  . '/../App/views/';
        @$this -> menu = [
            [
                "href" => "#",
                "name" => "Institucional",
            ],
            [
                "href" => "/portal-pacientes",
                "name" => "Portal Pacientes",
            ],
            [
                "href" => "/profyesp",
                "name" => "Profesionales y Especialidades",
            ],  
            [
                "href" => "#",
                "name" => "Informacion Util",
            ]
        ];

        @$this -> subMenuInstitucional = [
            [
                "href" => "/institucional/autoridades",
                "name" => "Autoridades"
            ],
            [
                "href" => "/institucional/historia",
                "name" => "Historia",
            ],
            [
                "href" => "/institucional/mision",
                "name" => "Mision",
            ],
            [
                "href" => "/institucional/valores",
                "name" => "Valores",
            ]
        ];

        @$this -> subMenuInformacionUtil = [
            [
                "href" => "/info-util/coberturasMedicas",
                "name" => "Coberturas Medicas",
            ],
            [
                "href" => "/info-util/novedades",
                "name" => "Novedades",
            ],
            [
                "href" => "/info-util/patologiasytratamientos",
                "name" => "Patologias y Tratamientos",
            ],
        ];

        $this->twig = new TwigClass();

        $this->parts['menu'] = $this->menu;
        $this->parts['subMenuInstitucional'] = $this->subMenuInstitucional;
        $this->parts['subMenuInformacionUtil'] = $this->subMenuInformacionUtil;

        if(!is_null($this -> modelName)) {
            $qb = new QueryBuilder($connection, $log);
            $model = new $this -> modelName;
            $model -> setQueryBuilder($qb);
            $this -> setModel($model);
        }
    }

    
    public function setModel(Model $model) {
        $this -> model = $model;
    }


}