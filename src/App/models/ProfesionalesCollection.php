<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\App\Models\Profesional;
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