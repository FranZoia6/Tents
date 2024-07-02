<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class BeachResort extends Model {

    public $table = 'beach_resort';

    public $fields = [
        "name" => null,
        "description" => null,
        "city" => null,
        "state" => null,
    ];

    public function setName(string $name) {
        $this -> fields["name"] = $name;
    }

    public function setDescription(string $description) {
        $this -> fields["description"] = $description;
    }

    public function setCity(int $city) {
        $this -> fields["city"] = $city;
    }

    public function setState(int $state) {
        $this -> fields["state"] = $state;
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