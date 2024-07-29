<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Common\RequestOptions;



class MercadoPago extends Model {

    public function crearPreferencia($reservation, $units){
        // var_dump($reservation);
        // die();

        $client = new PreferenceClient();
        $request_options = new RequestOptions();
        $request_options->setCustomHeaders(["X-Idempotency-Key: <SOME_UNIQUE_VALUE>"]);

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
            'success' => 'http://localhost:8888/',
            'failure' => '/failure'
        ];
 
        $mpAccessToken = getenv('mercado_pago_access_token');
        MercadoPagoConfig::setAccessToken($mpAccessToken);
        
          $client = new PreferenceClient();
          $request_options = new RequestOptions();
          $request_options->setCustomHeaders(["X-Idempotency-Key: <SOME_UNIQUE_VALUE>"]);
          error_reporting(E_ERROR | E_PARSE);
          $preference = $client->create([
            "items"=> $items ,
            "player" => $payer,
            "backUrls" => $backUrls,
            false
          ]);

        return $preference;

    }

}


