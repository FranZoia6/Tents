<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class Service extends Model {

    public $table = 'service';

    public $fields = [
        "id" => null,
        "name" => null
    ];

    public function setName(string $name) {
        if (strlen($name) > 63) {
            throw new InvalidValueFormatException("El nombre de la ciudad no debe ser mayor a 63 caracteres");
        }
        $this -> fields["name"] = $name;
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

    public function setId(int $id) {
        $this -> fields["id"] = $id;
    }

}