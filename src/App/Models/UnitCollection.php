<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class UnitCollection extends Model {

    public $table = 'unit';

    public function getAll() {
        $units = $this -> queryBuilder -> select('unit');
        $units_collection = [];
        
        foreach ($units as $unit) {
            $newUnit = new Unit;
            $newUnit->setQueryBuilder($this->queryBuilder);
            $newUnit -> set($unit);
            $units_collection[] = $newUnit;
        }
    
        return $units_collection;
    }

    public function get($id){
        $newUnit = new Unit;
        $newUnit->setQueryBuilder($this->queryBuilder);
        $newUnit->load($id);
        return $newUnit;
    }

}