<?php

namespace Tents\App\models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class BeachResort extends Model {

    public $table = 'beach_resort';

    public $fields = [
        "nombre" => null,
        "description" => null,
        "city_id" => null,
        "state_id" => null,
    ];

    public function setNombre(string $nombre) {
        $this -> fields["nombre"] = $nombre;
    }

    public function setDescripcion(string $description) {
        $this -> fields["description"] = $description;
    }

    public function setCity(int $city_id) {
        $this -> fields["city_id"] = $city_id;
    }

    public function setState(int $state_id) {
        $this -> fields["state_id"] = $state_id;
    }

    public function set(array $values) {
        foreach(array_keys($this -> fields) as $field) {
            if (!isset($values[$field])) {
                continue;
            }
            $method = "set" . ucfirst($field);
            $this -> $method($values[$field]);
        }
    }

}