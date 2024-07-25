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
        $data = json_decode(file_get_contents("php://input"), true);
        var_dump($_POST);
        var_dump($data);
        die("feliz");
        /*
         * $data = [
         *   "beachResortId" => 2,
         *   "startDate" => "2024-07-16",
         *   "endDate" => "2024-07-16",
         *   "selectedUnits" => ["tspan16"],
         *   "firstName" => "franco",
         *   "lastName" => "parzanese",
         *   "email" => "franco@example.com",
         *   "phone" => "2346-492180",
         *   "promo" => ""
         * ];
         */
        $menu = $this->menu;
        $titulo = "Confirmar reserva";
        $beachResort = new BeachResortCollection;
        $beachResort->setQueryBuilder($this->model->queryBuilder);
        $data["beachResortName"] = $beachResort->get($data["beachResortId"])->fields["name"];
        echo $this->twig->render("portal-user/reservationConfirmation.view.twig", compact("menu", "titulo", "data"));
    }


    public function edit() {

    }

    public function set() {

    }

}