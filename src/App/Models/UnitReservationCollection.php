<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class UnitReseravationCollection extends Model {

    public $table = 'unit_reservation';

    public function getAll() {
        $units_reservation = $this -> queryBuilder -> select('unit_reservation');
        $units_reservation_collection = [];
        
        foreach ($units_reservation as $unit_reservation) {
            $newUnitReservation = new UnitReservation;
            $newUnitReservation->setQueryBuilder($this->queryBuilder);
            $newUnitReservation -> set($unit_reservation);
            $units_reservation_collection[] = $newUnitReservation;
        }
    
        return $units_reservation_collection;
    }

    public function get($id){
        $newUnitReservation = new UnitReservation;
        $newUnitReservation->setQueryBuilder($this->queryBuilder);
        $newUnitReservation->load($id);
        return $newUnitReservation;
    }

}