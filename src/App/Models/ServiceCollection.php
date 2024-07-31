<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\App\Models\Service;
use DateTime;

class ServiceCollection extends Model {

    public $table = 'service';

    public function getAll() {
        $services = $this -> queryBuilder -> select('service');
        $services_collection = [];
        
        foreach ($services as $service) {
            $newservice = new Service;
            //$newservice->setQueryBuilder($this->queryBuilder);
            $newservice -> set($service);
            $services_collection[] = $newservice;
        }
    
        return $services_collection;
    }

    public function get($id){
        $service = new Service;
        $service->setQueryBuilder($this->queryBuilder);
        $service->load($id);
        return $service;
    }

    // public function getByCity($cityId) {
    //     return $this->queryBuilder->select('beach_resort', ['city' => $cityId]);
    // }

}