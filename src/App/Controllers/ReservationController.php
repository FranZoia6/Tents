<?php

namespace Tents\App\Controllers;

use Tents\Core\Controller;
use Tents\Core\Database\QueryBuilder;
use Tents\App\Models\ReservationCollection;
use Tents\App\Models\Reservation;


class ReservationController extends Controller {

    public string $viewsDir;
    public $menu;

    public ?string $modelName = ReservationCollection::class;
       
    public function searchUnitsFree() {

        $beachResortId = $this->request->get('id');
        $start_date = $this->request->get('start_date');
        $end_date = $this->request->get('end_date');

        // $unitCollection = new UnitCollection;
        // $unitCollection ->setQueryBuilder($this->model->queryBuilder);
        // $units = $unitCollection->getAll();

        // $unitReservationCollection = new UnitReservationCollection;
        // $unitReservationCollection ->setQueryBuilder($this->model->queryBuilder);
        // $unitsReservation = $unitReservationCollection->getAll();

        $occupatedTents = $this -> model -> getOccupatedTents($beachResortId, $start_date, $end_date);

        //var_dump($prueba);
        //die;
        
    }

    public function edit() {

    }

    public function set() {

    }

}