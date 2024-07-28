<?php

namespace Tents\App\Controllers;

use Tents\Core\Controller;
use Tents\Core\Database\QueryBuilder;
use Tents\App\Models\CityCollection;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;



$mpAccessToken = getenv('mercado_pago_access_token');
// Set the token the SDK's config
MercadoPagoConfig::setAccessToken($mpAccessToken);
// (Optional) Set the runtime enviroment to LOCAL if you want to test on localhost
// Default value is set to SERVER
MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);




class MercadoLibreController extends Controller {

    // Step 3: Initialize the API client
    public function makePayment() {

        {
            // Fill the data about the product(s) being pruchased
            $product1 = array(
                "id" => "1234567890",
                "title" => "Product 1 Title",
                "description" => "Product 1 Description",
                "currency_id" => "BRL",
                "quantity" => 12,
                "unit_price" => 9.90
            );
        
            $product2 = array(
                "id" => "9012345678",
                "title" => "Product 2 Title",
                "description" => "Product 2 Description",
                "currency_id" => "BRL",
                "quantity" => 5,
                "unit_price" => 19.90
            );
        
            // Mount the array of products that will integrate the purchase amount
            $items = array($product1, $product2);
            $preference->items;
        
            // Retrieve information about the user (use your own function)
            $user = getSessionUser();
        
            $payer = array(
                "name" => $user->name,
                "surname" => $user->surname,
                "email" => $user->email,
            );
        
            // Create the request object to be sent to the API when the preference is created

            $request = createPreferenceRequest($item, $payer);
        
            // Instantiate a new Preference Client
            $client = new PreferenceClient();
        
            try {
                // Send the request that will create the new preference for user's checkout flow
                $preference = $client->create($request);
        
                // Useful props you could use from this object is 'init_point' (URL to Checkout Pro) or the 'id'
                return $preference;
            } catch (MPApiException $error) {
                // Here you might return whatever your app needs.
                // We are returning null here as an example.
                return null;
            }
        }

    }
}
    