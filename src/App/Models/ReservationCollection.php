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
        'INNER JOIN reservation ON (reservation.id = unit_reservation.reservation) ' . 
        'WHERE unit.beachResort =  :beachResortId ' . 
        'AND ((reservation.from < :start_date AND reservation.to >= :start_date) ' . 
        'OR reservation.from = :start_date ' . 
        'OR (reservation.from > :start_date AND reservation.from <= :end_date))'; 

        $params = [
            ":beachResortId" => $beachResortId,
            ":start_date" => $start_date,
            ":end_date" => $end_date
        ];

        $reservation = $this -> queryBuilder -> querySql($query, $params);

        $units = $this -> queryBuilder -> select("unit", ["beachResort" => $beachResortId]);

        // Extrae los "id" de $idReservas usando array_column y array_map
        $reservationIds = array_map(function($item) {
            return $item['id'];
        }, $reservation);

        // Agrega la clave "free" a las unidades.
        return array_map(function ($unit) use ($reservationIds) {
            if (in_array($unit["id"], $reservationIds)) {
                $unit["free"] = false;
            } else {
                $unit["free"] = true;
            }
            return $unit;
        }, $units);
    }


    public function insertReservation($values) {
        try {
           return $this->queryBuilder->insert($this->table, $values);

        } catch (DatabaseException $e) {
            // Manejar el error de la base de datos, mostrar mensaje, registrar, etc.
            echo "Ocurrió un error al insertar el turno: " . $e->getMessage();
        } catch (InvalidDataException $e) {
            // Manejar el error de datos inválidos, mostrar mensaje, registrar, etc.
            echo "Los datos del turno son inválidos: " . $e->getMessage();
        } catch (InvalidValueFormatException $e) {
            // Manejar el error de formato de valor inválido, mostrar mensaje, registrar, etc.
            echo "El formato de un valor proporcionado es inválido: " . $e->getMessage();
        }
    }

    public function updateReservation($id, $newState) {
        try {
            // Asumiendo que $this->queryBuilder tiene un método update
            $this->queryBuilder->update($this->table, ['payed' => $newState, 'id' => $id]);
        } catch (DatabaseException $e) {
            // Manejar el error de la base de datos, mostrar mensaje, registrar, etc.
            echo "Ocurrió un error al actualizar el estado del resort: " . $e->getMessage();
        } catch (InvalidDataException $e) {
            // Manejar el error de datos inválidos, mostrar mensaje, registrar, etc.
            echo "Los datos proporcionados son inválidos: " . $e->getMessage();
        } catch (InvalidValueFormatException $e) {
            // Manejar el error de formato de valor inválido, mostrar mensaje, registrar, etc.
            echo "El formato del valor proporcionado es inválido: " . $e->getMessage();
        }
    }

    public function deleteReservation($id) {
        try {
            // Asumiendo que $this->queryBuilder tiene un método update
            $this->queryBuilder->delete($this->table, ['id' => $id]);
        } catch (DatabaseException $e) {
            // Manejar el error de la base de datos, mostrar mensaje, registrar, etc.
            echo "Ocurrió un error al actualizar el estado del resort: " . $e->getMessage();
        } catch (InvalidDataException $e) {
            // Manejar el error de datos inválidos, mostrar mensaje, registrar, etc.
            echo "Los datos proporcionados son inválidos: " . $e->getMessage();
        } catch (InvalidValueFormatException $e) {
            // Manejar el error de formato de valor inválido, mostrar mensaje, registrar, etc.
            echo "El formato del valor proporcionado es inválido: " . $e->getMessage();
        }
    }

}