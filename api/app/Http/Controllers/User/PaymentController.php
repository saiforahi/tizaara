<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Order;
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
                'order_id' => $request->query('order_id'), 
                'discsount_amount' => null, 
                'disc_percent' => null, 
                'client_ip' => $request->ip(), 
                'customer_name' => $request->query('name'), 
                'customer_phone' => $request->query('phone'), 
                'email' => $request->query('email'), 
                'customer_address' => $request->query('address'), 
                'customer_city' => $request->query('city'), 
                'customer_state' => $request->query('division'), 
                'customer_postcode' => $request->query('zip'), 
                'customer_country' => $request->query('country'),
                'value1'=> $request->query('value1'),
            );
            $shurjopay_service = new ShurjopayController(); 
            return $shurjopay_service->checkout($info);
        }
        catch(Exception $e){
            return $e;
        }
    }

    public function update_order_payment_status(Request $req){
        try{
            $req->validate([
                'transaction_id'=>'required|exists:orders,transaction_id',
                'order_id'=>'required|string'
            ]);
            Order::where('transaction_id',$req->transaction_id)->update([
                'shurjopay_order_id'=>$req->order_id,
                'payment_status'=>true,
                'payment_type'=>1,
            ]);
            return response()->json(['success'=>true,'message'=>'Payment Status updated'],200);
        }
        catch (Exception $e){
            return response()->json(['success'=>false,'error'=>$e->getMessage()],503);
        }
    }
}
