<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class ServiceBeachResort extends Model {

    public $table = 'service_beach_resort';

    public $fields = [
        "service_id" => null,
        "resort_id" => null,
    ];

    public function setId(int $id) {
        $this -> fields["id"] = $id;
    }

    public function setService(int $service_id) {
        $this -> fields["service_id"] = $service_id;
    }

    public function setResort(int $resort_id) {
        $this -> fields["resort_id"] = $resort_id;
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