<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;

class PaymentController extends Controller
{
    //
    public function shurjopay_checkout(Request $request){
        try{
            $info = array( 
                'currency' => "BDT", 
                'amount' => $request->query('amount'), 
                'order_id' => "123", 
                'discsount_amount' => null, 
                'disc_percent' => null, 
                'client_ip' => "127.0.0.1", 
                'customer_name' => "shaif", 
                'customer_phone' => "azad", 
                'email' => "shaif.rahi@northsouth.edu", 
                'customer_address' => "BRA", 
                'customer_city' => "Dhaka", 
                'customer_state' => "Dhaka", 
                'customer_postcode' => "1229", 
                'customer_country' => "BD"
            );
            $shurjopay_service = new ShurjopayController(); 
            return $shurjopay_service->checkout($info);
        }
        catch(Exception $e){
            return $e;
        }
    }
}
