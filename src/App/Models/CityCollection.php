<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\App\Models\City;
use DateTime;

class CityCollection extends Model {

    public $table = 'city';

    public function getAll() {
        $cities = $this -> queryBuilder -> select('city');
        $city_collection = [];
        foreach ($cities as $city) {
            $newCity = new City;
            #$newCity->setQueryBuilder($this->queryBuilder);
            $newCity -> set($city);
            $city_collection[] = $newCity;
        }
    
        return $city_collection;
    }

    

    public function get($id){
        $newCity = new City;
        $newCity->setQueryBuilder($this->queryBuilder);
        $newCity->load($id);
        return $newCity;
    }

    public function insertCity($values) {
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

    public function updateCity($city) {
        $params = [
            "id" => $city -> fields['id'],
            "name" => $city -> fields['name'],
            "img" => $city -> fields['img']
        ];

        try {
            $this->queryBuilder->update($this->table, $params);
        } catch (DatabaseException $e) {
            echo "Ocurrió un error al actualizar la ciudad: " . $e->getMessage();
        }

    }

}