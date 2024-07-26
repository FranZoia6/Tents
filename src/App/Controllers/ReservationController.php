<?php

namespace Tents\App\Controllers;

use Tents\Core\Controller;
use Tents\Core\Database\QueryBuilder;
use Tents\App\Models\BeachResortCollection;
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
        header('Content-Type: application/json');
        echo json_encode($occupatedTents, JSON_UNESCAPED_UNICODE);
    }


    
    public function datosReservation(){
        $data = $_POST;
        $menu = $this->menu;
        $titulo = "Confirmar reserva";
        $beachResort = new BeachResortCollection;
        $beachResort->setQueryBuilder($this->model->queryBuilder);
        $reservation = new Reservation;
        // $date = new DateTime();

        $reservation->setDate(date('Y-m-d'));  //($date->formate('Y-m-d'));
        $reservation->setFrom($data['startDate']);
        $reservation->setTo($data['endDate']);
        $reservation->setFirstName($data['firstName']);
        $reservation->setLastName($data['lastName']);
        $reservation->setEmail($data['email']);
        $reservation->setPhone($data['phone']);
        $reservation->setReservationAmount($data['reservationAmount']);
        $reservation->setIsPayed(0);
        $reservation->setManual(0);
        $reservation->setDiscountAmount(0);


        $reservationId = $this->model->insertReservation($reservation);

        echo $this->twig->render("portal-user/reservationConfirmation.view.twig", compact("menu", "titulo", "data"));
    }


    public function edit() {

    }

    public function set() {

    }

}