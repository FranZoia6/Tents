<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class City extends Model {

    public $table = 'city';

    public $fields = [
        "id" => null,
        "name" => null,
        "lat" => null,
        "lon" => null,
        "img" => null
    ];

    public function setName(string $nombre) {
        if (strlen($nombre) > 63) {
            throw new InvalidValueFormatException("El nombre de la ciudad no debe ser mayor a 63 caracteres");
        }
        $this -> fields["name"] = $nombre;
    }

    public function setLat(string $lat) {
        $this -> fields["lat"] = $lat;
    }

    public function setLon(string $lon) {
        $this -> fields["lon"] = $lon;
    }

    public function setImg(string $img) {
        $this -> fields["img"] = $img;
    }
    
    public function setId(int $id) {
        $this -> fields["id"] = $id;
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