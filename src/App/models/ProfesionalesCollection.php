<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Profesional;
use DateTime;

class TurnosCollection extends Model {

    public $table = 'profesional';

    public function getProfesionales() {
        $profesionales = $this -> queryBuilder -> select('profesional');
        $profesionalesCollection = [];
        
        foreach ($profesionales as $profesional) {
            $newProfesional = new Profesional;
            $newProfesional -> set($profesional);
            $profesionalesCollection[] = $newProfesional;
        }
    
        return $profesionalesCollection;
    }
}