<?php

namespace Tents\App\models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class City extends Model {

    public $table = 'shade';

    public $fields = [
        "nombre" => null,
    ];

    public function setNombre(string $nombre) {
        if (strlen($nombre) > 63) {
            throw new InvalidValueFormatException("El nombre de la ciudad no debe ser mayor a 63 caracteres");
        }
        $this -> fields["nombre"] = $nombre;
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