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


        $query = 'SELECT DISTINCT unit.id FROM unit '. 
        'INNER JOIN unit_reservation ON (unit_reservation.unit = unit.id) '. 
        'INNER JOIN reservation ON (reservation.id = unit_reservation.reservation) '. 
        'WHERE unit.beachResort = '. $beachResortId. 
        " AND ((reservation.from < '$start_date' AND reservation.to >= '$start_date') ". 
        "OR reservation.from = '$start_date' ". 
        "OR (reservation.from > '$start_date' AND reservation.from <= '$end_date'))"; 

        $reservation = $this -> queryBuilder -> querySql($query);



        $units = $this -> queryBuilder -> querySql("select * from unit where beachResort = $beachResortId");

        // Extraer los "id" de $idReservas usando array_column y array_map
        $idReservasIds = array_map(function($item) {
            return $item['id'];
        }, $reservation);

        // Filtrar $unidades por aquellos elementos cuyo "id" no está en $idReservasIds
        $unidadesFiltradas = array_filter($units, function($unidad) use ($idReservasIds) {
            return !in_array($unidad['id'], $idReservasIds);
        });

        var_dump($idReservasIds);
        // var_dump($units);  
        var_dump($unidadesFiltradas); 
        die;

    // $prueba = $this -> queryBuilder -> join("unit", "unit_reservation", "unit.id = unit_reservation.unit",
    // ['*'], ['unit.beachResort' => $beachResortId]);

    // foreach ($prueba as $item) {
    //     $reservation = $this -> queryBuilder -> select('reservation', [
    //                                                 'id' => $item['reservation'],
    //                                                 'reservation.from' => ['>=', date("Y-m-d", strtotime($start_date))],
    //                                                 'reservation.to' => ['<=', date("Y-m-d", strtotime($end_date))]
    //                                                 ]);                                    
    //     var_dump($reservation);
    //     die;
    // }


        // $unitsReservation = $this -> queryBuilder -> join("reservation", "unit_reservation", "reservation.id = unit_reservation.reservation",
        // ['*'], []);

    }

}