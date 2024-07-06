<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class ServiceBeachResort extends Model {

    public $table = 'service_beach_resort';

    public $fields = [
        "id" => null,
        "service" => null,
        "beachResort" => null,
    ];

    public function setId(int $id) {
        $this -> fields["id"] = $id;
    }

    public function setService(int $service) {
        $this -> fields["service"] = $service;
    }

    public function setBeachResort(int $beachResort) {
        $this -> fields["beachResort"] = $beachResort;
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