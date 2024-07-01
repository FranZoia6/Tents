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
        "city_id" => null,
        "state_id" => null,
    ];

    public function setName(string $name) {
        $this -> fields["name"] = $name;
    }

    public function setDescription(string $description) {
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