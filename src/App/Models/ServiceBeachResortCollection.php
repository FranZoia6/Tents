<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class ServiceBeachResortCollection extends Model {

    public $table = 'service_beach_resort';

    public function getAll() {
        $service_beach_resorts = $this -> queryBuilder -> select('service_beach_resort');
        $service_beach_resort_collection = [];
        
        foreach ($service_beach_resorts as $service_beach_resort) {
            $newServiceBeach = new ServiceBeachResort;
            $newServiceBeach->setQueryBuilder($this->queryBuilder);
            $newServiceBeach -> set($service_beach_resort);
            $service_beach_resort_collection[] = $newServiceBeach;
        }
    
        return $service_beach_resort_collection;
    }

    public function get($id){
        $newServiceBeach = new ServiceBeachResort;
        $newServiceBeach->setQueryBuilder($this->queryBuilder);
        $newServiceBeach->load($id);
        return $newServiceBeach;
    }

    public function getByBeachResort($BeachResortId) {
        return $this->queryBuilder->select($this->table, ['beachResort' => $BeachResortId]);
    }

    public function insert($service) {

        try {
            return $this->queryBuilder->insert($this->table, $service);
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