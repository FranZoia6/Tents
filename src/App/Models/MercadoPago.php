<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Common\RequestOptions;

use MercadoPago\SDK;
use MercadoPago\Resources\Payment;
use MercadoPago\Preference;
use MercadoPago\Client\Payment\PaymentClient;





class MercadoPago extends Model {
    

    public function crearPago($id){

        $mpAccessToken = getenv('mercado_pago_access_token');
        MercadoPagoConfig::setAccessToken($mpAccessToken);

        error_reporting(E_ERROR | E_PARSE);
        $client = new PaymentClient();
        $payment = $client->get($id);
        return $payment;

        //     case "plan":
        //         $plan = MercadoPago\Plan::find_by_id($POST["data"]["id"]);
        //         break;
        //     case "subscription":
        //         $plan = MercadoPago\Subscription::find_by_id($POST["data"]["id"]);
        //         break;
        //     case "invoice":
        //         $plan = MercadoPago\Invoice::find_by_id($POST["data"]["id"]);
        //         break;
        //     case "point_integration_wh":
        //         // $_POST contiene la informaciòn relacionada a la notificaciòn.
        //         break;
            
        
// Configuración de MercadoPago
    
}

    public function crearPreferencia($reservation, $units){


        foreach($units as $unit){
            $items[] = [
                "title" => "Unidad N" . $unit->fields['number'],
                "quantity" => 1,
                "unit_price" => $unit->fields['price'],
            ];
        }

        $payer = [
            "name" => $reservation->fields['firstName'],
            "surname" => $reservation->fields['lastName'],
            "email" => $reservation->fields['email'],
        ];

        $backUrls = [
            'success' => 'http://localhost:8888/approvedReservation?id=' . $reservation->fields['id'],
            'failure' => 'http://localhost:8888/reservationDenied?id=' . $reservation->fields['id']
        ];
 
        $mpAccessToken = getenv('mercado_pago_access_token');
        MercadoPagoConfig::setAccessToken($mpAccessToken);
        
          $client = new PreferenceClient();
          $request_options = new RequestOptions();
          $request_options->setCustomHeaders(["X-Idempotency-Key: <SOME_UNIQUE_VALUE>"]);
          error_reporting(E_ERROR | E_PARSE);
          $preference = $client->create([
            "items"=> $items ,
            "payer" => $payer,
            "back_urls" => $backUrls,
            'notification_url' => 'https://69c1-181-230-212-69.ngrok-free.app/approvedReservation',
            "external_reference" => $reservation->fields['id']
          ]);


        return $preference;

    }

}


