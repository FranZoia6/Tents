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

    public function getByBeachResort($BeachResortId) {
        $units = $this->queryBuilder->select($this->table, ['beachResort' => $BeachResortId]);
        $units_collection = [];
        
        foreach ($units as $unit) {
            $newUnit = new Unit;
            //$newUnit->setQueryBuilder($this->queryBuilder);
            $newUnit -> set($unit);
            $units_collection[] = $newUnit;
        }
    
        return $units_collection;
    }

    public function insertUnit($values) {
        try {
            $this->queryBuilder->insert($this->table, $values);
        } catch (DatabaseException $e) {
            // Manejar el error de la base de datos, mostrar mensaje, registrar, etc.
            echo "Ocurrió un error al insertar la unidad " . $e->getMessage();
        } catch (InvalidDataException $e) {
            // Manejar el error de datos inválidos, mostrar mensaje, registrar, etc.
            echo "Los datos de la unidad son inválidos: " . $e->getMessage();
        } catch (InvalidValueFormatException $e) {
            // Manejar el error de formato de valor inválido, mostrar mensaje, registrar, etc.
            echo "El formato de un valor proporcionado es inválido: " . $e->getMessage();
        }
    }

    public function updatePriceUnit($unit) {
        $params = [
            "price" => $unit -> fields['price']
        ];

        try {
            $this->queryBuilder->update($this->table, $params);
        } catch (DatabaseException $e) {
            echo "Ocurrió un error al actualizar la ciudad: " . $e->getMessage();
        }

    }

}