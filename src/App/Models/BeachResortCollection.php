<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\App\Models\BeachResort;
use DateTime;

class BeachResortCollection extends Model {

    public $table = 'beach_resort';

    public function getAll() {
        $beach_resorts = $this -> queryBuilder -> select('beach_resort');
        $beach_resorts_collection = [];
        
        foreach ($beach_resorts as $beach_resort) {
            $newBeachResort = new BeachResort;
            $newBeachResort->setQueryBuilder($this->queryBuilder);
            $newBeachResort -> set($beach_resort);
            $beach_resorts_collection[] = $newBeachResort;
        }
    
        return $beach_resorts_collection;
    }

    public function get($id){
        $newBeachResort = new BeachResort;
        $newBeachResort->setQueryBuilder($this->queryBuilder);
        $newBeachResort->load($id);
        return $newBeachResort;
    }

    public function getByCity($cityId) {
        return $this->queryBuilder->select('beach_resort', ['city' => $cityId]);
    }

}