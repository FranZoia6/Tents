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

    public function getOccupatedTents($beachResortId, $start_date, $end_date) {

        $prueba = $this -> queryBuilder -> join("unit", "unit_reservation", "unit.id = unit_reservation.unit",
        ['*'], ['unit.beachResort' => $beachResortId]);

        foreach ($prueba as $item) {
            $reservation = $this -> queryBuilder -> select('reservation', [
                                                        'id' => $item['reservation'],
                                                        'reservation.from' => ['>=', date("Y-m-d", strtotime($start_date))],
                                                        'reservation.to' => ['<=', date("Y-m-d", strtotime($end_date))]
                                                        ]);                                    
            var_dump($reservation);
            die;
        }


        // $unitsReservation = $this -> queryBuilder -> join("reservation", "unit_reservation", "reservation.id = unit_reservation.reservation",
        // ['*'], []);

    }

}