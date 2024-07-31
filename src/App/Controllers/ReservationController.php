<?php

namespace Tents\App\Controllers;

use Tents\Core\Controller;
use Tents\Core\Database\QueryBuilder;
use Tents\App\Models\BeachResortCollection;
use Tents\App\Models\UnitReservation;
use Tents\App\Models\UnitReservationCollection;
use Tents\App\Models\ReservationCollection;
use Tents\App\Models\UnitCollection;
use Tents\App\Models\Reservation;
use Tents\App\Models\MercadoPago;
use Tents\Core\Traits\Loggable;

use MercadoPago\MercadoPagoConfig;

use MercadoPago\SDK;
use MercadoPago\Payment;









// use MercadoPago\Client\Preference\PreferenceClient;
// use MercadoPago\MercadoPagoConfig;
// use MercadoPago\Client\Common\RequestOptions;

class ReservationController extends Controller {

    public string $viewsDir;
    public $menu;
    Use Loggable;

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
        $reservation->setPayed(0);
        $reservation->setManual(0);
        $reservation->setDiscountAmount(0);
        $data['selectedUnits']=  array_map('intval', explode(',', $data['selectedUnits']));
        $reservationId = $this->model->insertReservation($reservation);
        $reservation->setId($reservationId);

        $unitCollection = new UnitCollection;
        $unitCollection -> setQueryBuilder($this->model->queryBuilder);
        $units = [];

        foreach($data['selectedUnits'] as $unit){
            $unitReservation = new UnitReservation;
            $unitReservationCollection = new UnitReservationCollection;
            $unitReservationCollection->setQueryBuilder($this->model->queryBuilder);
            $unitReservation->setReservation($reservationId);
            $unitReservation->setUnit($unit);
            $unitFromCollection = $unitCollection->get($unit);
            if ($unitFromCollection) {
                $units[] = $unitFromCollection;
            }

            $unitReservationCollection->insertUnitReservation($unitReservation);
        };


        $mercadoPago = new MercadoPago;

        $preference  = $mercadoPago->crearPreferencia($reservation, $units);
        var_dump($preference);
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


    public function approvedReservation() {       
       
        
        $body = file_get_contents('php://input');
        
        $data = json_decode($body, true);
        
        $id = $data['data']['id'] ?? null;
        
        if ($id) {
            $mercadoPago = new MercadoPago;
            $payment = $mercadoPago->crearPago($id);
        }

        if (isset($payment->status)) {
            if ($payment->status == 'approved') {
                if (isset($payment->external_reference)) {
                    $reservationId = $payment->external_reference;
                    $this->model->updateReservation($reservationId, 1);
                }
            } else {
                if (isset($payment->external_reference)) {
                    $reservationId = $payment->external_reference;
                    $this->model->deleteReservation($reservationId);
                }
            }
        } 
        
        http_response_code(200);
        


        // $reservationId = $this->request->get('id');
        // $status = $this->request->get('collection_status');
        // $menu = $this->menu;
        // $titulo = "Pago Aceptado";
        // $this->model->updateReservation($reservationId,1);
        // $reservationData = $this->reservationData($reservationId);
        // echo $this->twig->render('/portal-user/reservation.view.twig', compact('titulo','menu','reservationData'));

    }

    public function reservationDenied(){
        $reservationId = $this->request->get('id');
        $menu = $this->menu;
        $titulo = "Pago Denegado";
        $reservationData = $this->reservationData($reservationId);

        echo $this->twig->render('/portal-user/reservation.view.twig', compact('titulo','menu','reservationData'));
    }

    public function reservationData($reservationId){

        $reservation = $this->model->get($reservationId);
        $reservation->fields['reservationId'] = $reservationId;
        $unitReservationCollection = new UnitReservationCollection;
        $unitReservationCollection -> setQueryBuilder($this->model->queryBuilder);
        $unitReservations = $unitReservationCollection->unitsFronREservation($reservationId);


        foreach ($unitReservations as $unitReservation) {
            $units[] = $unitReservation['unit'];
        }

        $unitCollection = new UnitCollection;
        $unitCollection -> setQueryBuilder($this->model->queryBuilder);
        $unit = $unitCollection->get($units[0]);
        $beachResortId = $unit ->fields['beachResort'];

        $beachResortCollection = new BeachResortCollection;
        $beachResortCollection -> setQueryBuilder($this->model->queryBuilder);
        $beachResort = $beachResortCollection->get($beachResortId);


        $reservation->fields['beachResortName'] = $beachResort->fields['name'];



        $reservation->fields['units'] = $units;

        return $reservation;
    }


    public function edit() {

    }

    public function set() {

    }

}