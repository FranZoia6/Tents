<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\App\Models\BeachResort;
use DateTime;

class BeachResortCollection extends Model {

    public $table = 'beach_resort';

    public function getAll() {
        $beach_resorts = $this -> queryBuilder -> select('beach_resort');
        $beach_resorts_collection = [];
        
        foreach ($beach_resorts as $beach_resort) {
            $newBeachResort = new BeachResort;
            $newBeachResort->setQueryBuilder($this->queryBuilder);
            $newBeachResort -> set($beach_resort);
            $beach_resorts_collection[] = $newBeachResort;
        }
        return $beach_resorts_collection;
    }

    public function getEnable() {
        $beach_resorts = $this -> queryBuilder -> select('beach_resort',['state' => 1]);
        $beach_resorts_collection = [];
        
        foreach ($beach_resorts as $beach_resort) {
            $newBeachResort = new BeachResort;
            $newBeachResort->setQueryBuilder($this->queryBuilder);
            $newBeachResort -> set($beach_resort);
            $beach_resorts_collection[] = $newBeachResort;
        }
        return $beach_resorts_collection;
    }

    public function get($id){
        $newBeachResort = new BeachResort;
        $newBeachResort->setQueryBuilder($this->queryBuilder);
        $newBeachResort->load($id);
        return $newBeachResort;
    }

    public function getByCity($cityId) {
        return $this->queryBuilder->select('beach_resort', ['city' => $cityId, 'state' => 1]);
    }

    public function insertBeachResort($values) {
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

    public function updateBeachResortState($id, $newState) {
        try {
            // Asumiendo que $this->queryBuilder tiene un método update
            $this->queryBuilder->update($this->table, ['state' => $newState, 'id' => $id]);
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

    public function obtenerNombres() {

        // Definición de los joins
        $joins = [
            ['table' => 'city', 'condition' => 'beach_resort.city = city.id', 'type' => 'INNER'],
            ['table' => 'state_beach_resort', 'condition' => 'beach_resort.state = state_beach_resort.id', 'type' => 'INNER']
        ];

        // Llamada a la función join
        $resultado = $this->queryBuilder->join('beach_resort', $joins, ['beach_resort.id AS beach_resort_id, beach_resort.name AS beach_resort_name, city.name AS city_name, state_beach_resort.name AS state_beach_resort_name'], []);
        
        return $resultado;
    }

    public function obtenerCiudad($beachResortId) {

        // Definición de los joins
        $joins = [
            ['table' => 'city', 'condition' => 'beach_resort.city = city.id', 'type' => 'INNER'],
        ];
    
        // Llamada a la función join
        $resultado = $this->queryBuilder->join(
            'beach_resort', 
            $joins, 
            ['beach_resort.id AS beach_resort_id', 'beach_resort.name AS beach_resort_name', 'city.name AS city_name'],
            ['beach_resort.id' => $beachResortId]
        );
        
        return $resultado;
    }
    

}