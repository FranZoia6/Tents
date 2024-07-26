<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class Unit extends Model {

    public $table = 'unit';

    public $fields = [
        "beachResort" => null,
        "shade" => null,
        "number" => null,
        "price" => null,
    ];

    public function setBeachResort(int $beachresort_id) {
        $this -> fields["beachResort"] = $beachresort_id;
    }

    public function setShade(int $shade_id) {
        $this -> fields["shade"] = $shade_id;
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