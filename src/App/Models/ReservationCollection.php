<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class ReservationCollection extends Model {

    public $table = 'reservation';

    public function getAll() {
        $reservations = $this -> queryBuilder -> select('reservation');
        $reservations_collection = [];
        
        foreach ($reservations as $reservation) {
            $newReservation = new Reservation;
            $newReservation->setQueryBuilder($this->queryBuilder);
            $newReservation -> set($reservation);
            $reservations_collection[] = $newReservation;
        }
    
        return $reservations_collection;
    }

    public function get($id){
        $newReservation = new Reservation;
        $newReservation->setQueryBuilder($this->queryBuilder);
        $newReservation->load($id);
        return $newReservation;
    }

    public function join($table, $joinTable, $joinCondition, $selectColumns, $params) {
        return $this -> queryBuilder -> join($table, $joinTable, $joinCondition, $selectColumns, $params);
    }

}