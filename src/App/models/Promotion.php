<?php

namespace Tents\App\models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class Promotion extends Model {

    public $table = 'promotion';

    public $fields = [
        "nombre" => null,
        "code" => null,
        "discount" => null,
        "isPercentage" => null,
        "from" => null,
        "to" => null,
    ];

    public function setNombre(string $nombre) {
        $this -> fields["nombre"] = $nombre;
    }

    public function setCode(string $code) {
        $this -> fields["code"] = $code;
    }

    public function setDiscount(float $discount) {
        $this -> fields["discount"] = $discount;
    }

    public function setIsPercentage($isPercentage) {
        if (!is_bool($isPercentage)) {
            throw new InvalidValueFormatException("El campo debe ser booleano.");
        }
        $this -> fields["isPercentage"] = $isPercentage;
    }

    public function setFrom($from) {
        $this -> fields["from"] = $from;
    }

    public function setTo($to) {
        $this -> fields["to"] = $to;
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