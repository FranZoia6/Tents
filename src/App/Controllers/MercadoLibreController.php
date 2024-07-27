<?php

namespace Tents\App\Controllers;

use Tents\Core\Controller;
use Tents\Core\Database\QueryBuilder;
use Tents\App\Models\CityCollection;

use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;



// Step 2: Set production or sandbox access token
MercadoPagoConfig::setAccessToken("APP_USR-1339194868701317-072717-88afb019330b743a3baab55eaa8df448-236518782");
// Step 2.1 (optional - default is SERVER): Set your runtime enviroment from MercadoPagoConfig::RUNTIME_ENVIROMENTS
// In case you want to test in your local machine first, set runtime enviroment to LOCAL
MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);



class MercadoLibreController extends Controller {
    
    // Step 3: Initialize the API client
    
	public function makePayment() {


      

      
        

$client = new PreferenceClient();

$preference = $client->create([

    "items" => [

        [

            "title" => "Mi producto",

            "quantity" => 1,

            "unit_price" => 2000

        ]

    ]

]);
   
        }

}
