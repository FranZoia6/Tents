<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class UnitReservationCollection extends Model {

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

    public function unitsFronREservation($reservation){
        return  $this->queryBuilder->select($this->table, ['reservation' => $reservation]);

    }

    public function insertUnitReservation($values) {
        try {
            $this->queryBuilder->insert($this->table, $values);
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

}