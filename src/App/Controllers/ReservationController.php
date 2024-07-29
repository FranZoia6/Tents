<?php

namespace Tents\App\Controllers;

use Tents\Core\Controller;
use Tents\Core\Database\QueryBuilder;
use Tents\App\Models\BeachResortCollection;
use Tents\App\Models\UnitReservation;
use Tents\App\Models\UnitReservationCollection;
use Tents\App\Models\ReservationCollection;
use Tents\App\Models\Reservation;

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Common\RequestOptions;

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
        $titulo = "Resumen de la reserva";
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
        $data['selectedUnits']=  array_map('intval', explode(',', $data['selectedUnits']));
        $reservationId = $this->model->insertReservation($reservation);
        foreach($data['selectedUnits'] as $unit){
            $unitReservation = new UnitReservation;
            $unitReservationCollection = new UnitReservationCollection;
            $unitReservationCollection->setQueryBuilder($this->model->queryBuilder);
            $unitReservation->setReservation($reservationId);
            $unitReservation->setUnit($unit);
            $unitReservationCollection->insertUnitReservation($unitReservation);
        };


        $mpAccessToken = getenv('mercado_pago_access_token');
        MercadoPagoConfig::setAccessToken($mpAccessToken);
        
          $client = new PreferenceClient();
          $request_options = new RequestOptions();
          $request_options->setCustomHeaders(["X-Idempotency-Key: <SOME_UNIQUE_VALUE>"]);
          error_reporting(E_ERROR | E_PARSE);
          $preference = $client->create([
            "items"=> array(
              array(
                "title" => "My product",
                "quantity" => 1,
                "unit_price" => 2000
              )
            ),
            false
          ]);

        echo $this->twig->render("portal-user/reservationConfirmation.view.twig", compact("menu", "titulo", "data", 'reservationId', 'preference'));
    }

    public function adminReservation() {
        session_start();
        if (isset($_SESSION['logueado'])) {
            $titulo = "Reservas";
            $menu = $this->menuAdmin;
            $reservationCollection = new ReservationCollection;
            $reservationCollection ->setQueryBuilder($this->model->queryBuilder);
            $reservations = $reservationCollection->getAll();

            echo $this->twig->render('/portal-admin/adminReservation.view.twig',compact('menu','titulo','reservations'));
        }else {
            $mensajeError = 'Prueba';
            $menu = $this->menu;
            echo $this->twig->render('/portal-user/login.view.twig', ['mensajeError' => $mensajeError, 'menu' => $menu]);
        }
    }

    public function getReservation() {
        $reservationId = $this->request->get('reservation');
        $menu = $this->menuAdmin;
        $titulo = "Reserva";
        $reservation = $this->model->get($reservationId);

        $unitReservationCollection = new ReservationCollection;
        $unitReservationCollection ->setQueryBuilder($this->model->queryBuilder);
        $unitReservations = $unitReservationCollection->getAll();

        echo $this->twig->render('/portal-admin/reservation.view.twig', compact('titulo','menu','reservation'));
    }


    public function edit() {

    }

    public function set() {

    }

}