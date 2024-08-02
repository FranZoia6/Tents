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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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
        //var_dump($preference);
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

        $unitReservationCollection = new UnitReservationCollection;
        $unitReservationCollection ->setQueryBuilder($this->model->queryBuilder);
        $unitReservations = $unitReservationCollection->unitsFronREservation($reservationId);

        $unitCollection = new UnitCollection;
        $unitCollection -> setQueryBuilder($this->model->queryBuilder);
        $tents = 0;
        $parasol = 0;
        foreach ($unitReservations as $unitReservation) {   
            $shade = $unitCollection->get($unitReservation['unit'])->fields['shade'];
            if ($shade == 1) {
                $tents += 1;
            } else {
                $parasol += 1;
            }
        }
        
        echo $this->twig->render('/portal-admin/reservation.view.twig', compact('titulo','menu','reservation','tents','parasol'));
    }


    public function paymentStatus() {       
       
        
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
                    $reservationData = $this->reservationData($reservationId);

                    $mail = new PHPMailer(true);

                    try {
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.office365.com'; 
                        $mail->SMTPAuth   = true;
                        $mail->Username   = getenv("MAIL");
                        $mail->Password   = getenv("MAIL_PASSWORD"); 
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;
            
                        $mail->setFrom(getenv("MAIL"), 'Tents');
                        $mail->addAddress($reservationData->fields['email'], $reservationData->fields['firstName'] . ' ' . $reservationData->fields['lastName']);
                        
                        $mail->isHTML(true);
                        $mail->Subject = 'Ticket de Reserva 2.0';
                        $ticketHtml = $this->createTicketHtml($reservationData);
                        $mail->Body = $ticketHtml;
            
                        $mail->AltBody = 'Este es el cuerpo del correo electrónico en texto plano.';
            
                        $mail->send();
                        echo 'Correo enviado correctamente.';
                    } catch (Exception $e) {
                        echo "Error al enviar el correo: {$mail->ErrorInfo}";
                    }


                }
            } else {
                if (isset($payment->external_reference)) {
                    $reservationId = $payment->external_reference;
                    $this->model->deleteReservation($reservationId);
                }
            }
        } 
        
        http_response_code(200);
        


      

    }

    public function approvedReservation(){
        $reservationId = $this->request->get('id');
        $menu = $this->menu;
        $titulo = "Pago Aceptado";
        $this->model->updateReservation($reservationId,1);
        $reservationData = $this->reservationData($reservationId);

        echo $this->twig->render('/portal-user/reservation.view.twig', compact('titulo','menu','reservationData'));

    }

    public function reservationDenied(){
        $menu = $this->menu;
        $titulo = "Pago Denegado";
        $error = true; 
        $search = true;
        echo $this->twig->render('/portal-user/index.view.twig', compact('titulo','menu','error', 'search'));
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


    function createTicketHtml($reservationData) {
        // Accede a los campos del objeto
        $fields = $reservationData->fields;
    
        ob_start();
        ?>
        <div style="font-family: Arial, sans-serif; margin: 20px; padding: 20px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 5px;">
            <h1 style="font-size: 24px; color: #333;">Ticket de Reserva</h1>
            
            <h2 style="font-size: 18px; color: #555;">Información de la Reserva</h2>
            <p><strong>ID de Reserva:</strong> <?php echo htmlspecialchars($fields['id']); ?></p>
            <p><strong>Fecha de Reserva:</strong> <?php echo htmlspecialchars($fields['date']); ?></p>
            <p><strong>Fecha de Entrada:</strong> <?php echo htmlspecialchars($fields['from']); ?></p>
            <p><strong>Fecha de Salida:</strong> <?php echo htmlspecialchars($fields['to']); ?></p>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($fields['firstName'] . ' ' . $fields['lastName']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($fields['email']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($fields['phone']); ?></p>
            <p><strong>Nombre del Balneario:</strong> <?php echo htmlspecialchars($fields['beachResortName']); ?></p>
            
            <h2 style="font-size: 18px; color: #555;">Detalles de la Reserva</h2>
            <p><strong>Monto de la Reserva:</strong> $<?php echo number_format($fields['reservationAmount'], 2); ?></p>
            <p><strong>Descuento:</strong> $<?php echo number_format($fields['discountAmount'], 2); ?></p>
            <p><strong>Pagado:</strong> <?php echo $fields['payed'] ? 'Sí' : 'No'; ?></p>
            
            <h2 style="font-size: 18px; color: #555;">Unidades Reservadas</h2>
            <ul style="padding-left: 20px;">
                <?php foreach ($fields['units'] as $unit): ?>
                    <li>Unidad <?php echo htmlspecialchars($unit); ?></li>
                <?php endforeach; ?>
            </ul>
    
            <p style="margin-top: 20px; color: #666;">Gracias por reservar con nosotros. ¡Esperamos que disfrutes tu estancia!</p>
        </div>
        <?php
        return ob_get_clean();
    }
    
    
    
    



    public function edit() {

    }

    public function set() {

    }

}