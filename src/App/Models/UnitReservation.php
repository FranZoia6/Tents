<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class UnitReservation extends Model {

    public $table = 'unit_reservation';

    public $fields = [
        "reservation" => null,
        "unit" => null,
    ];

    public function setReservation(int $reservation_id) {
        $this -> fields["reservation"] = $reservation_id;
    }

    public function setUnit(int $unit_id) {
        $this -> fields["unit"] = $unit_id;
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