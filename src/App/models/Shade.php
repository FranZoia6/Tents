<?php

namespace Tents\App\models;

use Tents\Core\Model;

class Shade extends Model {

    public $table = 'shade';

    public $fields = [
        "nombre" => null,
    ];

    public function setNombre(string $nombre) {
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