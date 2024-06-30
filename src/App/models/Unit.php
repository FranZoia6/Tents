<?php

namespace Tents\App\models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class Unit extends Model {

    public $table = 'unit';

    public $fields = [
        "beachresort_id" => null,
        "shade_id" => null,
        "number" => null,
        "price" => null,
    ];

    public function setBeachResort(int $beachresort_id) {
        $this -> fields["beachresort_id"] = $beachresort_id;
    }

    public function setShade(int $shade_id) {
        $this -> fields["shade_id"] = $shade_id;
    }

    public function setNumber(int $number) {
        $this -> fields["number"] = $number;
    }

    public function setPrice(float $price) {
        $this -> fields["price"] = $price;
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