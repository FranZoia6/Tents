<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\App\Models\City;
use DateTime;

class CityCollection extends Model {

    public $table = 'city';

    public function getAll() {
        $cities = $this -> queryBuilder -> select('city');
        $city_collection = [];
        
        foreach ($cities as $city) {
            $newCity = new City;
            $newCity->setQueryBuilder($this->queryBuilder);
            $newCity -> set($city);
            $city_collection[] = $newCity;
        }
    
        return $city_collection;
    }

    public function get($id){
        $newCity = new City;
        $newCity->setQueryBuilder($this->queryBuilder);
        $newCity->load($id);
        return $newCity;
    }

}