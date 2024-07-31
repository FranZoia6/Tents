<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class BeachResort extends Model {

    public $table = 'beach_resort';

    public $fields = [
        "id" => null,
        "name" => null,
        "description" => null,
        "city" => null,
        "state" => null,
        "street" => null,
        "number" => null,
        "lat" => null,
        "lon" => null,
        "img" => null
    ];

    public function setId($id){
        $this->fields["id"] = $id;
    }

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

    public function setStreet(string $street) {
        $this -> fields["street"] = $street;
    }

    public function setNumber(int $number) {
        $this -> fields["number"] = $number;
    }

    public function setLat(string $lat) {
        $this -> fields["lat"] = $lat;
    }

    public function setLon(string $lon) {
        $this -> fields["lon"] = $lon;
    }

    public function setImg(string $img) {
        $this -> fields["img"] = $img;
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


    

    // public function load($id)
    // {
    //     $params = ["id"=>$id];
    //     $record = current($this->queryBuilder->select($this->table, $params));
    //     $this->set($record);
    // }



}